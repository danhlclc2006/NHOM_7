<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Database;
use App\Models\Tour;
use App\Models\CustomerInBooking;

class BookingController extends AdminBaseController
{
    // File: DA1/app/Controllers/Admin/BookingController.php

    public function index()
    {
        if (!Auth::check() || !Auth::isRole('admin')) {
            header('Location: /index.php/login');
            exit;
        }

        $db = Database::getConnection();

        // 1. Lấy tham số từ URL
        $search = $_GET['q'] ?? null;
        $status = $_GET['status'] ?? null;
        $date   = $_GET['date'] ?? null;

        // 2. Chuẩn bị câu SQL cơ bản
        $sql = "SELECT b.*, t.tour_name, 
            (SELECT phone FROM customers_in_booking WHERE booking_id = b.booking_id LIMIT 1) as contact_phone
            FROM bookings b 
            JOIN tours t ON b.tour_id = t.tour_id 
            WHERE 1=1"; // Kỹ thuật 1=1 giúp nối chuỗi AND dễ dàng

        $params = [];

        // 3. Xử lý logic lọc
        // Lọc theo Từ khóa (Mã vé, Tên tour, SĐT)
        if (!empty($search)) {
            $sql .= " AND (
            b.booking_id LIKE :search 
            OR t.tour_name LIKE :search
            OR EXISTS (
                SELECT 1 FROM customers_in_booking c 
                WHERE c.booking_id = b.booking_id 
                AND c.phone LIKE :search
            )
        )";
            $params['search'] = "%$search%";
        }

        // Lọc theo Trạng thái
        if (!empty($status)) {
            $sql .= " AND b.status = :status";
            $params['status'] = $status;
        }

        // Lọc theo Ngày khởi hành (start_date)
        if (!empty($date)) {
            $sql .= " AND b.start_date = :date";
            $params['date'] = $date;
        }

        // 4. Sắp xếp và Thực thi
        $sql .= " ORDER BY b.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll();

        $this->view('admin/bookings/index', ['bookings' => $rows]);
    }

    public function create()
    {
        Auth::requireRole(['admin']);
        $db = \App\Core\Database::getConnection();

        // Lấy danh sách tour để đổ vào select box
        $tours = $db->query("SELECT tour_id, tour_name FROM tours WHERE status = 'Hoạt động'")->fetchAll();

        $selectedTourId = $_GET['tour_id'] ?? null;
        $departures = [];

        // Nếu đã chọn tour, lấy danh sách lịch khởi hành của tour đó
        if ($selectedTourId) {
            $depModel = new \App\Models\TourDeparture();
            $departures = $depModel->getUpcomingByTour($selectedTourId);
        }

        $this->view('admin/bookings/create', [
            'step'       => 1,
            'tours'      => $tours,
            'selectedTourId' => $selectedTourId,
            'departures' => $departures
        ]);
    }

    public function prepare()
    {
        Auth::requireRole(['admin']);

        $departure_id = $_POST['departure_id']; // ID lịch khởi hành từ form
        $people_count = (int)$_POST['total_people'];

        // 1. Lấy thông tin lịch khởi hành
        $mDep = new \App\Models\TourDeparture();
        $departure = $mDep->find($departure_id);

        if (!$departure) {
            $_SESSION['error'] = "Không tìm thấy lịch khởi hành này.";
            return $this->redirect('?act=admin-bookings-create');
        }

        // 2. --- KHẮC PHỤC LỖI $tour ---
        // Từ lịch khởi hành, lấy ra tour_id để tìm thông tin Tour gốc (Tên, mô tả...)
        $mTour = new \App\Models\Tour(); // Hoặc use App\Models\Tour ở trên đầu file
        $tour  = $mTour->find($departure['tour_id']);
        // -----------------------------

        // 3. Kiểm tra chỗ trống dựa trên Lịch khởi hành
        $available = $departure['max_people'] - $departure['booked_count'];

        if ($people_count > $available) {
            $_SESSION['error'] = "Lịch ngày " . date('d/m', strtotime($departure['start_date'])) . " chỉ còn $available chỗ (Bạn nhập $people_count khách).";
            return $this->redirect('?act=admin-bookings-create');
        }

        // 4. Tính toán giá (Dùng giá của lịch khởi hành)
        $totalPrice = $departure['price'] * $people_count;

        $admin_user = Auth::user();
        $contact_phone = $_POST['contact_phone'] ?? 'N/A';

        $customer = [
            'full_name' => htmlspecialchars($admin_user['full_name'] ?? 'Admin'),
            'phone'     => $contact_phone,
            'email'     => htmlspecialchars($admin_user['email'] ?? 'N/A')
        ];

        // 5. Truyền cả $tour và $departure xuống View
        $this->view('admin/bookings/create', [
            'step'       => 2,
            'tour'       => $tour,       // <--- Đã có biến $tour để View hiển thị tên
            'departure'  => $departure,  // Truyền thêm cái này để View hiển thị ngày đi chính xác
            'customer'   => $customer,
            'preData'    => array_merge($_POST, [
                'contact_phone' => $contact_phone,
                'tour_id'       => $tour['tour_id'], // Giữ lại tour_id cho các form ẩn
                'start_date'    => $departure['start_date'] // Cập nhật ngày đi từ lịch đã chọn
            ]),
            'totalPrice' => $totalPrice
        ]);
    }

    public function store()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        $tour_id      = $_POST['tour_id'];
        $departure_id = $_POST['departure_id'];


        $user         = Auth::user();
        $created_by   = $user['user_id'];
        $start_date   = $_POST['start_date'];
        $total_people = $_POST['total_people'];
        $total_price  = $_POST['total_price'];
        $note         = $_POST['note'];
        $passengers   = $_POST['passengers'] ?? [];
        $contact_phone = $_POST['contact_phone'] ?? null;

        $depModel = new \App\Models\TourDeparture();
        $departure = $depModel->find($departure_id);
        $unitPrice = $departure['price'];

        // Đếm số người lớn (Trẻ em = 0đ)
        $adultCount = 0;
        foreach ($passengers as $p) {
            if (isset($p['customer_type']) && $p['customer_type'] === 'Người lớn') {
                $adultCount++;
            }
        }
        // Tổng tiền thực tế = Số người lớn * Giá vé
        $total_price = $adultCount * $unitPrice;

        try {
            $db->beginTransaction();
            $sql = "INSERT INTO bookings (tour_id, departure_id, created_by, total_people, total_price, start_date, status, note) 
            VALUES (:tid, :did, :uid, :ppl, :price, :date, 'Chờ xác nhận', :note)";

            $stmt = $db->prepare($sql);
            $stmt->execute([
                'tid'   => $tour_id,
                'did'   => $departure_id,
                'uid'   => $created_by,
                'ppl'   => $total_people,
                'price' => $total_price,
                'date'  => $start_date,
                'note'  => $note
            ]);
            $bookingId = $db->lastInsertId();

            $cusModel = new CustomerInBooking();
            foreach ($passengers as $index => $p) {
                $phone_to_save = ($index == 0) ? $contact_phone : ($p['phone'] ?? null);

                $cusModel->add([
                    'booking_id'      => $bookingId,
                    'full_name'       => $p['full_name'],
                    'customer_type'   => $p['customer_type'] ?? 'Người lớn',
                    'gender'          => $p['gender'],
                    'dob'             => !empty($p['dob']) ? $p['dob'] : null,
                    'phone'           => $phone_to_save,
                    'passport_number' => $p['passport_number'],
                    'note'            => $p['note']
                ]);
            }
            $depModel = new \App\Models\TourDeparture();
            $depModel->updateBookedCount($departure_id);
            $db->commit();
            $_SESSION['flash'] = "Đặt tour thành công! Booking #$bookingId đang chờ xác nhận.";
            $this->redirect('?act=admin-bookings');
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            $this->redirect('?act=admin-bookings-create');
        }
    }

    public function edit()
    {
        Auth::requireRole(['admin']);
        $id = $_GET['id'] ?? 0;
        $db = Database::getConnection();

        $sql = "SELECT b.*, t.tour_name 
                FROM bookings b 
                JOIN tours t ON b.tour_id = t.tour_id 
                WHERE b.booking_id = :id";
        $stmt = $db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            $_SESSION['error'] = "Không tìm thấy Booking #$id";
            return $this->redirect('?act=admin-bookings');
        }

        $mCus = new CustomerInBooking();
        $customers = $mCus->getByBookingId($id);

        $this->view('admin/bookings/edit', [
            'booking' => $booking,
            'customers' => $customers
        ]);
    }

    public function update()
    {
        Auth::requireRole(['admin']);
        $db = Database::getConnection();

        $booking_id = $_POST['booking_id'];
        $status     = $_POST['status'];
        $passengers = $_POST['customers'] ?? [];

        try {
            $db->beginTransaction();
            $sqlBooking = "UPDATE bookings SET status = :st WHERE booking_id = :id";
            $stmt = $db->prepare($sqlBooking);
            $stmt->execute(['st' => $status, 'id' => $booking_id]);

            foreach ($passengers as $cusId => $data) {
                $sqlUpdate = "UPDATE customers_in_booking SET 
                              full_name = :fn, 
                              gender = :gd, 
                              dob = :dob, 
                              phone = :ph, 
                              passport_number = :pp, 
                              note = :nt
                              WHERE customer_id = :cid AND booking_id = :bid";

                $stmtUp = $db->prepare($sqlUpdate);
                $stmtUp->execute([
                    'fn' => $data['full_name'],
                    'gd' => $data['gender'],
                    'dob' => !empty($data['dob']) ? $data['dob'] : null,
                    'ph' => $data['phone'],
                    'pp' => $data['passport_number'],
                    'nt' => $data['note'],
                    'cid' => $cusId,
                    'bid' => $booking_id
                ]);
            }

            $db->commit();
            $_SESSION['flash'] = "Cập nhật Booking #$booking_id thành công!";
            $this->redirect("?act=admin-bookings");
        } catch (\Exception $e) {
            $db->rollBack();
            $_SESSION['error'] = "Lỗi cập nhật: " . $e->getMessage();
            $this->redirect("?act=admin-bookings-edit&id=$booking_id");
        }
    }
}
