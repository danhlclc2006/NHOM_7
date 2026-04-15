<?php
// ============================================================
// config/config.php
// ============================================================
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'car_marketplace');
define('DB_CHARSET', 'utf8mb4');

define('APP_NAME', 'CarMarket VN');
define('APP_URL', 'http://localhost/Nhom7');
define('APP_VERSION', '1.0.0');

define('UPLOAD_PATH', __DIR__ . '/../public/images/uploads/');
define('UPLOAD_URL', APP_URL . '/public/images/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/webp']);

define('ITEMS_PER_PAGE', 10);

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Error reporting (tắt khi production)
error_reporting(E_ALL);
ini_set('display_errors', 1);
