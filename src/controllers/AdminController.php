<?php
// ============================================================
// src/controllers/AdminController.php
// ============================================================
class AdminController {
    private Car $carModel;
    private User $userModel;
    private Message $msgModel;
    private Brand $brandModel;

    public function __construct() {
        $this->carModel   = new Car();
        $this->userModel  = new User();
        $this->msgModel   = new Message();
        $this->brandModel = new Brand();
    }

    public function dashboard(): void {
        requireAdmin();
        $stats = [
            'total_users'    => $this->userModel->countAll(),
            'car_by_status'  => $this->carModel->countByStatus(),
            'unread_messages'=> $this->msgModel->countUnread(),
        ];
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }

    // ---- USERS ----
    public function userList(): void {
        requireAdmin();
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $users = $this->userModel->getAll($page);
        $total = $this->userModel->countAll();
        require_once __DIR__ . '/../views/admin/user-list.php';
    }

    public function toggleUser(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->userModel->toggleActive($id);
        redirectWithMessage('admin-users', 'success', 'Cập nhật trạng thái thành công!');
    }

    public function deleteUser(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        if ($this->userModel->delete($id)) {
            redirectWithMessage('admin-users', 'success', 'Xóa người dùng thành công!');
        } else {
            redirectWithMessage('admin-users', 'danger', 'Không thể xóa tài khoản admin!');
        }
    }

    // ---- CARS ----
    public function carList(): void {
        requireAdmin();
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $status = sanitize($_GET['status'] ?? '');
        $cars   = $this->carModel->getAllAdmin($page, $status);
        $total  = $this->carModel->countAllAdmin($status);
        require_once __DIR__ . '/../views/admin/car-list.php';
    }

    public function approveCar(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->carModel->updateStatus($id, 'approved');
        redirectWithMessage('admin-cars', 'success', 'Xe đã được duyệt!');
    }

    public function rejectCar(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->carModel->updateStatus($id, 'rejected');
        redirectWithMessage('admin-cars', 'warning', 'Xe đã bị từ chối!');
    }

    public function deleteCar(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->carModel->delete($id);
        redirectWithMessage('admin-cars', 'success', 'Xóa xe thành công!');
    }

    // ---- MESSAGES ----
    public function messageList(): void {
        requireAdmin();
        $page     = max(1, (int)($_GET['page'] ?? 1));
        $messages = $this->msgModel->getAll($page);
        $total    = $this->msgModel->countAll();
        require_once __DIR__ . '/../views/admin/message-list.php';
    }

    public function deleteMessage(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->msgModel->delete($id);
        redirectWithMessage('admin-messages', 'success', 'Đã xóa tin nhắn!');
    }

    // ---- BRANDS ----
    public function brandList(): void {
        requireAdmin();
        $brands = $this->brandModel->getAll();
        require_once __DIR__ . '/../views/admin/brand-list.php';
    }

    public function addBrand(): void {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('admin-brands'); }
        $name = sanitize($_POST['name'] ?? '');
        if (!empty($name)) {
            $this->brandModel->create($name);
            redirectWithMessage('admin-brands', 'success', 'Thêm hãng xe thành công!');
        }
        redirectWithMessage('admin-brands', 'danger', 'Tên hãng không được để trống!');
    }

    public function deleteBrand(): void {
        requireAdmin();
        $id = (int)($_GET['id'] ?? 0);
        $this->brandModel->delete($id);
        redirectWithMessage('admin-brands', 'success', 'Xóa hãng xe thành công!');
    }
}
