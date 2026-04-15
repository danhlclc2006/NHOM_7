<?php
// ============================================================
// src/controllers/AuthController.php
// ============================================================
class AuthController {
    private User $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function showLogin(): void {
        if (isLoggedIn()) redirect('home');
        require_once __DIR__ . '/../views/auth/login.php';
    }

    public function login(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->showLogin();
            return;
        }

        $email    = sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $errors   = [];

        if (empty($email))    $errors[] = 'Vui lòng nhập email.';
        if (empty($password)) $errors[] = 'Vui lòng nhập mật khẩu.';

        if (empty($errors)) {
            $user = $this->userModel->findByEmail($email);
            if ($user && password_verify($password, $user['password'])) {
                if (!$user['is_active']) {
                    $errors[] = 'Tài khoản của bạn đã bị khóa.';
                } else {
                    $_SESSION['user'] = $user;
                    redirectWithMessage('home', 'success', 'Đăng nhập thành công! Chào mừng, ' . $user['name']);
                }
            } else {
                $errors[] = 'Email hoặc mật khẩu không đúng.';
            }
        }

        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data']   = ['email' => $email];
        redirect('login');
    }

    public function register(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once __DIR__ . '/../views/auth/register.php';
            return;
        }

        $data   = [
            'name'     => sanitize($_POST['name'] ?? ''),
            'email'    => sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'phone'    => sanitize($_POST['phone'] ?? ''),
        ];
        $errors = [];

        if (strlen($data['name']) < 2)                  $errors[] = 'Tên phải có ít nhất 2 ký tự.';
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
        if (strlen($data['password']) < 6)              $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
        if ($this->userModel->emailExists($data['email'])) $errors[] = 'Email đã được sử dụng.';

        if (empty($errors)) {
            $this->userModel->create($data);
            redirectWithMessage('login', 'success', 'Đăng ký thành công! Hãy đăng nhập.');
        }

        $_SESSION['form_errors'] = $errors;
        $_SESSION['form_data']   = $data;
        redirect('register');
    }

    public function logout(): void {
        session_destroy();
        header("Location: " . APP_URL . "/index.php?act=login");
        exit;
    }
}
