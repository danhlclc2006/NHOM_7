<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Đăng nhập hệ thống</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: url("https://dynamic-media-cdn.tripadvisor.com/media/photo-o/1d/b9/99/5c/moraine-lake-photo-taken.jpg?w=1400&h=800&s=1") no-repeat center center;
      background-size: cover;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', sans-serif;
      color: #fff;
    }

    .login-card {
      width: 420px;
      background: #13151c;
      padding: 40px;
      border-radius: 22px;
      box-shadow: 0 0 35px rgba(0, 0, 0, 0.55);
      animation: fadeIn .5s ease;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(12px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .form-control {
      background: #1b1e27;
      border: 1px solid #2b2f3b;
      color: #fff;
      height: 48px;
      border-radius: 12px;
      padding-left: 40px;
    }

    .form-control:focus {
      background: #171a23;
      border-color: #00ffc6;
      box-shadow: 0 0 10px rgba(0, 255, 198, 0.6);
      color: #fff;
    }

    .input-icon {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #00ffc6;
    }

    .btn-gradient {
      background: linear-gradient(90deg, #00ffc6, #007aff);
      border: none;
      height: 48px;
      border-radius: 10px;
      color: #000;
      font-weight: 700;
      letter-spacing: 0.5px;
      width: 100%;
    }

    .btn-gradient:hover {
      opacity: .9;
    }

    a {
      color: #42aaff;
      text-decoration: none;
    }

    a:hover {
      text-decoration: underline;
    }

    .social-btn {
      background: #1b1e27;
      border: 1px solid #2b2f3b;
      color: #fff;
      height: 42px;
      border-radius: 10px;
      width: 100%;
    }

    .social-btn:hover {
      background: #222531;
    }

    .divider {
      display: flex;
      align-items: center;
      gap: 10px;
      margin: 20px 0;
      color: #666a7b;
    }

    .divider::before,
    .divider::after {
      content: "";
      height: 1px;
      flex: 1;
      background: #2b2f35;
    }
  </style>
</head>

<body>

  <section class="login-card">

    <h1 class="fw-bold text-center mb-1">Sign In</h1>
    <div class="text-center mb-4" style="color:#8890a2">Access your account</div>

    <?php if (!empty($error)) : ?>
      <div class="alert alert-danger py-2 mb-3 fw-bold">
        <?= htmlspecialchars($error) ?>
      </div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>?act=login-post" method="POST">

      <!-- Email -->
      <div class="mb-3 position-relative">
        <i class="bi bi-envelope input-icon"></i>
        <input type="email" name="email" class="form-control" placeholder="Email" required>
      </div>

      <!-- Password -->
      <div class="mb-3 position-relative">
        <i class="bi bi-lock input-icon"></i>
        <input type="password" name="password" class="form-control" placeholder="Password" required>
      </div>

      <button type="submit" class="btn-gradient mt-2 mb-2">
        SIGN IN
      </button>

      <div class="divider text-center">OR</div>

      

      <p class="text-center mt-2" style="color:#9da1b5;">
        New here?
        <a href="<?= BASE_URL ?>?act=register">Create an account</a>
      </p>
    </form>

  </section>

</body>

</html>