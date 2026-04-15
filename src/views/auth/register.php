<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        * { font-family: 'Be Vietnam Pro', sans-serif; }
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #0f3460 100%);
            min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 2rem 0;
        }
        .card {
            border-radius: 16px; box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            overflow: hidden; width: 100%; max-width: 450px; border: none;
        }
        .card-header {
            background: linear-gradient(135deg, #1a1a2e, #e74c3c);
            padding: 1.8rem; text-align: center; color: #fff;
        }
        .card-header h1 { font-size: 1.6rem; font-weight: 700; margin: 0; }
        .form-control { border-radius: 8px; border: 1.5px solid #e0e0e0; }
        .form-control:focus { border-color: #e74c3c; box-shadow: 0 0 0 3px rgba(231,76,60,0.1); }
        .btn-primary { background: #e74c3c; border-color: #e74c3c; border-radius: 8px; font-weight: 600; }
        .btn-primary:hover { background: #c0392b; border-color: #c0392b; }
        .input-group-text { background: #f8f8f8; border: 1.5px solid #e0e0e0; }
        label { font-weight: 500; font-size: 0.9rem; }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h1><i class="fas fa-user-plus mr-2"></i>Tạo tài khoản</h1>
        <p class="mb-0 mt-1" style="opacity:0.8; font-size:0.9rem;">CarMarket VN — Mua bán xe uy tín</p>
    </div>
    <div class="card-body p-4">
        <?php
        $errors   = $_SESSION['form_errors'] ?? [];
        $formData = $_SESSION['form_data'] ?? [];
        unset($_SESSION['form_errors'], $_SESSION['form_data']);
        ?>
        <?php if ($errors): ?>
            <div class="alert alert-danger py-2">
                <?php foreach ($errors as $e): ?><div>• <?= sanitize($e) ?></div><?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/index.php?act=register">
            <div class="form-group">
                <label>Họ và tên <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                    <input type="text" name="name" class="form-control"
                           value="<?= sanitize($formData['name'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                </div>
            </div>
            <div class="form-group">
                <label>Email <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                    <input type="email" name="email" class="form-control"
                           value="<?= sanitize($formData['email'] ?? '') ?>" placeholder="email@example.com" required>
                </div>
            </div>
            <div class="form-group">
                <label>Mật khẩu <span class="text-danger">*</span></label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                    <input type="password" name="password" class="form-control" placeholder="Tối thiểu 6 ký tự" required>
                </div>
            </div>
            <div class="form-group">
                <label>Số điện thoại</label>
                <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                    <input type="text" name="phone" class="form-control"
                           value="<?= sanitize($formData['phone'] ?? '') ?>" placeholder="09xxxxxxxx">
                </div>
            </div>
            <button type="submit" class="btn btn-primary btn-block btn-lg">
                <i class="fas fa-user-plus mr-2"></i>Đăng ký ngay
            </button>
        </form>
        <hr>
        <p class="text-center mb-0" style="font-size:0.9rem;">
            Đã có tài khoản? <a href="<?= APP_URL ?>/index.php?act=login" style="color:#e74c3c;font-weight:600;">Đăng nhập</a>
        </p>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
