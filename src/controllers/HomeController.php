<?php
// ============================================================
// src/controllers/HomeController.php
// ============================================================
class HomeController {
    private Car $carModel;
    private Brand $brandModel;

    public function __construct() {
        $this->carModel   = new Car();
        $this->brandModel = new Brand();
    }

    public function index(): void {
        $filters = [
            'keyword'   => sanitize($_GET['keyword'] ?? ''),
            'brand_id'  => (int)($_GET['brand_id'] ?? 0),
            'year'      => (int)($_GET['year'] ?? 0),
            'min_price' => (float)($_GET['min_price'] ?? 0),
            'max_price' => (float)($_GET['max_price'] ?? 0),
        ];
        $page  = max(1, (int)($_GET['page'] ?? 1));
        $cars  = $this->carModel->getApproved($filters, $page);
        $total = $this->carModel->countApproved($filters);
        $brands = $this->brandModel->getAll();
        $years = range(date('Y'), 2000);

        require_once __DIR__ . '/../views/home/index.php';
    }

    public function detail(): void {
        $id = (int)($_GET['id'] ?? 0);
        $car = $this->carModel->findById($id);
        if (!$car || $car['status'] !== 'approved') {
            $this->notFound();
            return;
        }
        $this->carModel->incrementViews($id);
        $images  = $this->carModel->getImages($id);
        $brands  = $this->brandModel->getAll();
        require_once __DIR__ . '/../views/home/detail.php';
    }

 

    public function notFound(): void {
        http_response_code(404);
        require_once __DIR__ . '/../views/home/404.php';
    }
}
