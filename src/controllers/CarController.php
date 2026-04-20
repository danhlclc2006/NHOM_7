<?php
// ============================================================
// src/controllers/CarController.php
// ============================================================
class CarController {
    private Car $carModel;
    private Brand $brandModel;

    public function __construct() {
        $this->carModel   = new Car();
        $this->brandModel = new Brand();
    }

    // Danh sách xe của user hiện tại
    public function myList(): void {
        requireLogin();
        $userId = currentUser()['id'];
        $page   = max(1, (int)($_GET['page'] ?? 1));
        $cars   = $this->carModel->getByUser($userId, $page);
        $total  = $this->carModel->countByUser($userId);
        require_once __DIR__ . '/../views/car/my-list.php';
    }

    // Form đăng tin
    public function formAdd(): void {
        requireLogin();
        $brands = $this->brandModel->getAll();
        require_once __DIR__ . '/../views/car/form-add.php';
    }

    // Xử lý đăng tin
    public function add(): void {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('my-cars'); }

        $data   = [
            'user_id'      => currentUser()['id'],
            'brand_id'     => (int)($_POST['brand_id'] ?? 0),
            'title'        => sanitize($_POST['title'] ?? ''),
            'price'        => (float)($_POST['price'] ?? 0),
            'year'         => (int)($_POST['year'] ?? date('Y')),
            'mileage'      => (int)($_POST['mileage'] ?? 0),
            'fuel_type'    => $_POST['fuel_type'] ?? 'xang',
            'transmission' => $_POST['transmission'] ?? 'tu_dong',
            'color'        => sanitize($_POST['color'] ?? ''),
            'description'  => sanitize($_POST['description'] ?? ''),
        ];
        $errors = [];

        if (empty($data['title']))       $errors[] = 'Tiêu đề không được để trống.';
        if ($data['price'] <= 0)         $errors[] = 'Giá phải lớn hơn 0.';
        if ($data['brand_id'] <= 0)      $errors[] = 'Vui lòng chọn hãng xe.';

        if (empty($errors)) {
            $carId = $this->carModel->create($data);
            if ($carId) {
                // Upload ảnh
                if (!empty($_FILES['images']['name'][0])) {
                    $isPrimary = true;
                    foreach ($_FILES['images']['tmp_name'] as $idx => $tmp) {
                        $file = [
                            'name'     => $_FILES['images']['name'][$idx],
                            'type'     => $_FILES['images']['type'][$idx],
                            'tmp_name' => $tmp,
                            'error'    => $_FILES['images']['error'][$idx],
                            'size'     => $_FILES['images']['size'][$idx],
                        ];
                        $filename = uploadImage($file, 'car');
                        if ($filename) {
                            $this->carModel->addImage($carId, $filename, $isPrimary);
                            $isPrimary = false;
                        }
                    }
                }
                redirectWithMessage('my-cars', 'success', 'Đăng tin thành công! Chờ admin duyệt.');
            }
        }

        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data']   = $data;
        redirect('form-add-car');
    }

    // Form chỉnh sửa
    public function formEdit(): void {
        requireLogin();
        $id  = (int)($_GET['id'] ?? 0);
        $car = $this->carModel->findById($id);

        if (!$car || (!isAdmin() && !$this->carModel->isOwner($id, currentUser()['id']))) {
            redirectWithMessage('my-cars', 'danger', 'Không có quyền chỉnh sửa xe này.');
        }

        $brands = $this->brandModel->getAll();
        $images = $this->carModel->getImages($id);
        require_once __DIR__ . '/../views/car/form-edit.php';
    }

    // Cập nhật
    public function update(): void {
        requireLogin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('my-cars'); }
        $id = (int)($_POST['id'] ?? 0);

        if (!isAdmin() && !$this->carModel->isOwner($id, currentUser()['id'])) {
            redirectWithMessage('my-cars', 'danger', 'Không có quyền chỉnh sửa.');
        }

        $data = [
            'brand_id'     => (int)($_POST['brand_id'] ?? 0),
            'title'        => sanitize($_POST['title'] ?? ''),
            'price'        => (float)($_POST['price'] ?? 0),
            'year'         => (int)($_POST['year'] ?? date('Y')),
            'mileage'      => (int)($_POST['mileage'] ?? 0),
            'fuel_type'    => $_POST['fuel_type'] ?? 'xang',
            'transmission' => $_POST['transmission'] ?? 'tu_dong',
            'color'        => sanitize($_POST['color'] ?? ''),
            'description'  => sanitize($_POST['description'] ?? ''),
        ];

        if ($this->carModel->update($id, $data)) {
            // Upload ảnh mới nếu có
            if (!empty($_FILES['images']['name'][0])) {
                foreach ($_FILES['images']['tmp_name'] as $idx => $tmp) {
                    $file = [
                        'name'     => $_FILES['images']['name'][$idx],
                        'type'     => $_FILES['images']['type'][$idx],
                        'tmp_name' => $tmp,
                        'error'    => $_FILES['images']['error'][$idx],
                        'size'     => $_FILES['images']['size'][$idx],
                    ];
                    $filename = uploadImage($file, 'car');
                    if ($filename) $this->carModel->addImage($id, $filename, false);
                }
            }
            redirectWithMessage('my-cars', 'success', 'Cập nhật xe thành công!');
        } else {
            redirectWithMessage('edit-car&id=' . $id, 'danger', 'Cập nhật thất bại!');
        }
    }

    // Xóa xe
    public function delete(): void {
        requireLogin();
        $id = (int)($_GET['id'] ?? 0);

        if (!isAdmin() && !$this->carModel->isOwner($id, currentUser()['id'])) {
            redirectWithMessage('my-cars', 'danger', 'Không có quyền xóa.');
        }

        if ($this->carModel->delete($id)) {
            redirectWithMessage('my-cars', 'success', 'Xóa xe thành công!');
        } else {
            redirectWithMessage('my-cars', 'danger', 'Xóa thất bại!');
        }
    }
}
