<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Be Vietnam Pro', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
        }
        .login-card {
            background: #fff; border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden; width: 100%; max-width: 420px;
        }
        .login-header {
            background: linear-gradient(135deg, #1a1a2e, #e74c3c);
            padding: 2rem; text-align: center; color: #fff;
        }
        .login-header h1 { font-size: 1.8rem; font-weight: 700; margin: 0; }
        .login-header p  { opacity: 0.8; margin: 0.5rem 0 0; font-size: 0.9rem; }
        .login-body { padding: 2rem; }
        .form-control { border-radius: 8px; border: 1.5px solid #e0e0e0; padding: 0.7rem 1rem; }
        .form-control:focus { border-color: #e74c3c; box-shadow: 0 0 0 3px rgba(231,76,60,0.1); }
        .btn-login {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            border: none; border-radius: 8px; padding: 0.8rem;
            font-weight: 600; font-size: 1rem; color: #fff; width: 100%;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(231,76,60,0.4); color: #fff; }
        .input-group-text { background: #f8f8f8; border: 1.5px solid #e0e0e0; color: #888; }
        label { font-weight: 500; font-size: 0.9rem; color: #444; }
    </style>
</head>
<body>
<div class="login-card">
    <div class="login-header">
        <h1><i class="fas fa-car mr-2"></i>CarMarket VN</h1>
        <p>Đăng nhập để tiếp tục</p>
    </div>
    <div class="login-body">
        <?php
        $errors   = $_SESSION['form_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_data']);
        $flash = getFlash();
        ?>
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?> py-2"><?= sanitize($flash['message']) ?></div>
        <?php endif; ?>
        <?php if ($errors): ?>
            <div class="alert alert-danger py-2">
                <?php foreach ($errors as $e): ?><div>• <?= sanitize($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/index.php?act=login">
            <div class="form-group">
                <label>Email</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    </div>
                    <input type="email" name="email" class="form-control"
                           value="<?= sanitize($formData['email'] ?? '') ?>"
                           placeholder="email@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Mật khẩu</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    </div>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>
            <button type="submit" class="btn-login mt-2">
                <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
            </button>
        </form>
        <hr>
        <p class="text-center mb-0" style="font-size:0.9rem;">
            Chưa có tài khoản?
            <a href="<?= APP_URL ?>/index.php?act=register" style="color:#e74c3c;font-weight:600;">Đăng ký ngay</a>
        </p>
        <p class="text-center mt-2 mb-0" style="font-size:0.82rem; color:#999;">
            Demo: admin@carmarket.vn / password
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
