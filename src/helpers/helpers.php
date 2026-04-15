<?php
// ============================================================
// src/helpers/helpers.php
// ============================================================

function redirect(string $act): void {
    header("Location: " . APP_URL . "/index.php?act=$act");
    exit;
}

function redirectWithMessage(string $act, string $type, string $message): void {
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    redirect($act);
}

function getFlash(): ?array {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function isLoggedIn(): bool {
    return isset($_SESSION['user']);
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        redirectWithMessage('login', 'warning', 'Vui lòng đăng nhập để tiếp tục.');
    }
}

function requireAdmin(): void {
    requireLogin();
    if ($_SESSION['user']['role'] !== 'admin') {
        redirectWithMessage('home', 'danger', 'Bạn không có quyền truy cập trang này.');
    }
}

function currentUser(): ?array {
    return $_SESSION['user'] ?? null;
}

function isAdmin(): bool {
    return isLoggedIn() && $_SESSION['user']['role'] === 'admin';
}

function sanitize(string $input): string {
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

function formatPrice(float $price): string {
    if ($price >= 1_000_000_000) {
        return number_format($price / 1_000_000_000, 2) . ' tỷ';
    }
    return number_format($price / 1_000_000, 0) . ' triệu';
}

function formatNumber(int $n): string {
    return number_format($n, 0, ',', '.');
}

function uploadImage(array $file, string $prefix = 'img'): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;
    if ($file['size'] > MAX_FILE_SIZE) return false;
    if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) return false;

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = $prefix . '_' . uniqid() . '.' . $ext;
    $dest = UPLOAD_PATH . $filename;

    if (!is_dir(UPLOAD_PATH)) mkdir(UPLOAD_PATH, 0755, true);
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return $filename;
    }
    return false;
}

function paginationLinks(int $total, int $perPage, int $current, string $baseUrl): string {
    $totalPages = (int)ceil($total / $perPage);
    if ($totalPages <= 1) return '';

    $html = '<ul class="pagination pagination-sm m-0">';
    $html .= '<li class="page-item ' . ($current <= 1 ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '&page=' . ($current - 1) . '">«</a></li>';

    for ($i = 1; $i <= $totalPages; $i++) {
        $html .= '<li class="page-item ' . ($i === $current ? 'active' : '') . '">';
        $html .= '<a class="page-link" href="' . $baseUrl . '&page=' . $i . '">' . $i . '</a></li>';
    }

    $html .= '<li class="page-item ' . ($current >= $totalPages ? 'disabled' : '') . '">';
    $html .= '<a class="page-link" href="' . $baseUrl . '&page=' . ($current + 1) . '">»</a></li>';
    $html .= '</ul>';
    return $html;
}

function statusBadge(string $status): string {
    return match($status) {
        'approved' => '<span class="badge badge-success">Đã duyệt</span>',
        'pending'  => '<span class="badge badge-warning">Chờ duyệt</span>',
        'rejected' => '<span class="badge badge-danger">Từ chối</span>',
        'sold'     => '<span class="badge badge-secondary">Đã bán</span>',
        default    => '<span class="badge badge-light">' . $status . '</span>',
    };
}

function fuelLabel(string $fuel): string {
    return match($fuel) {
        'xang'   => 'Xăng',
        'dau'    => 'Dầu',
        'dien'   => 'Điện',
        'hybrid' => 'Hybrid',
        default  => $fuel,
    };
}

function transmissionLabel(string $t): string {
    return match($t) {
        'so_san'  => 'Số sàn',
        'tu_dong' => 'Tự động',
        default   => $t,
    };
}
