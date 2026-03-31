<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Models\HdvProfile;
use App\Models\User;
use App\Models\TourDeparture;
use App\Models\TourAssignment;
use App\Models\DepartureProgress;

class HdvController extends AdminBaseController
{

    public function index()
    {
        Auth::requireRole(['admin']);
        $model = new HdvProfile();
        $hdvs = $model->getAllHdvs();
        $this->view('admin/hdv/index', ['hdvs' => $hdvs]);
    }


    public function create()
    {
        Auth::requireRole(['admin']);
        $this->view('admin/hdv/create');
    }

    public function store()
    {
        Auth::requireRole(['admin']);

        $fullName = $_POST['full_name'];
        $email    = $_POST['email'];
        $password = $_POST['password'];
        $phone    = $_POST['phone'];

        // 1. Kiểm tra Email đã tồn tại chưa
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $_SESSION['error'] = "Email '$email' đã tồn tại trong hệ thống!";
            return $this->redirect('?act=admin-hdv-create');
        }

        // 2. Tạo tài khoản User (Role = hdv)
        // Hash mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            $newUserId = $userModel->create([
                'full_name' => $fullName,
                'email'     => $email,
                'password'  => $hashedPassword,
                'phone'     => $phone,
                'role'      => 'hdv',
                'status'    => '1'
            ]);

            $avatarPath = null;
            if (!empty($_FILES['avatar']['name'])) {
                $targetDir = "uploads/avatars/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
                $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
                if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetDir . $fileName)) {
                    $avatarPath = $targetDir . $fileName;
                }
            }

            // 4. Tạo Hồ sơ HDV (HdvProfile)
            $profileModel = new HdvProfile();
            $profileData = [
                'dob'    => $_POST['date_of_birth'] ?? null,
                'lang'   => $_POST['languages'] ?? '',
                'cert'   => $_POST['certificate'] ?? '',
                'exp'    => $_POST['experience_years'] ?? 0,
                'health' => $_POST['health_status'] ?? 'Tốt',
                'class'  => $_POST['classification'] ?? 'Nội địa',
                'avt'    => $avatarPath
            ];

            // Hàm saveProfile của bạn đã viết xử lý cả INSERT nếu chưa có
            $profileModel->saveProfile($newUserId, $profileData);

            $_SESSION['flash'] = "Thêm mới HDV thành công! Tài khoản đã sẵn sàng.";
            $this->redirect('?act=admin-hdv');
        } catch (\Exception $e) {
            $_SESSION['error'] = "Lỗi hệ thống: " . $e->getMessage();
            $this->redirect('?act=admin-hdv-create');
        }
    }

    public function edit()
    {
        Auth::requireRole(['admin']);
        $userId = $_GET['id'] ?? 0;

        $model = new HdvProfile();
        $hdv = $model->getDetail($userId);

        $depModel = new TourDeparture();
        $history = $depModel->getHistoryByHdv($userId);

        if (!$hdv) {
            $_SESSION['flash'] = "Không tìm thấy HDV";
            $this->redirect('?act=admin-hdv');
        }

        $this->view('admin/hdv/edit', [
            'hdv' => $hdv,
            'history' => $history
        ]);
    }

    public function update()
    {
        Auth::requireRole(['admin']);
        $userId = $_POST['user_id'];

        // 1. Upload ảnh (Giữ nguyên logic cũ)
        $avatarPath = $_POST['old_avatar'] ?? null;
        if (!empty($_FILES['avatar']['name'])) {
            $targetDir = "uploads/avatars/";
            if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
            $fileName = time() . "_" . basename($_FILES["avatar"]["name"]);
            if (move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetDir . $fileName)) {
                $avatarPath = $targetDir . $fileName;
            }
        }

        $model = new HdvProfile();
        $data = [
            'dob' => !empty($_POST['date_of_birth']) ? $_POST['date_of_birth'] : null,
            'lang' => $_POST['languages'],
            'cert' => $_POST['certificate'],
            'exp' => $_POST['experience_years'],
            'health' => $_POST['health_status'],
            'class'  => $_POST['classification'] ?? 'Nội địa', // Dòng mới thêm
            'avt' => $avatarPath
        ];
        $model->saveProfile($userId, $data);

        $_SESSION['flash'] = "Cập nhật hồ sơ HDV thành công!";
        $this->redirect('?act=admin-hdv-edit&id=' . $userId);
    }

    public function detail()
    {
        Auth::requireRole(['admin']);
        $userId = $_GET['id'] ?? 0;

        $model = new HdvProfile();
        $hdv = $model->getDetail($userId); // Lấy thông tin cá nhân

        if (!$hdv) {
            $_SESSION['error'] = "Không tìm thấy HDV";
            $this->redirect('?act=admin-hdv');
        }

        $depModel = new TourDeparture();

        // 1. Lấy tour ĐANG dẫn (active) - Logic: chưa hoàn tất và chưa hủy
        // Giả sử ta lấy status = 'Đang đi' hoặc các status tương tự
        $db = \App\Core\Database::getConnection();
        $sqlCurrent = "SELECT d.*, t.tour_name, t.image
                       FROM tour_departures d
                       JOIN tours t ON d.tour_id = t.tour_id
                       WHERE d.hdv_id = :uid AND d.status = 'Đang đi'";
        $currentTour = $db->prepare($sqlCurrent);
        $currentTour->execute(['uid' => $userId]);
        $currentTours = $currentTour->fetchAll();

        // 2. Lấy lịch sử tour ĐÃ dẫn (Completed)
        $history = $depModel->getHistoryByHdv($userId);

        $this->view('admin/hdv/detail', [
            'hdv' => $hdv,
            'currentTours' => $currentTours,
            'history' => $history
        ]);
    }

    public function departure()
    {
        Auth::requireRole(['admin']);
        $departureId = $_GET['id'] ?? 0;

        // Lấy thông tin chuyến đi
        $mAssign = new TourAssignment();
        $assign = $mAssign->find($departureId);

        if (!$assign) {
            $_SESSION['error'] = "Không tìm thấy thông tin chuyến đi.";
            $this->redirect('?act=admin-hdv');
        }

        // Lấy thông tin HDV
        $hdvUser = (new User())->find($assign['hdv_id']);
        $assign['hdv_name'] = $hdvUser['full_name'] ?? 'Chưa phân công';
        $assign['hdv_phone'] = $hdvUser['phone'] ?? '';

        // Lấy tiến độ và ảnh check-in
        $mProgress = new DepartureProgress();
        $schedules = $mProgress->getProgressByDeparture($departureId);

        // Gọi view mới tên là departure
        $this->view('admin/hdv/departure', [
            'assign' => $assign,
            'schedules' => $schedules
        ]);
    }
}
