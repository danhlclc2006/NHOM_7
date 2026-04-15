<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Admin' ?> | <?= APP_NAME ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <style>
        :root { --brand-dark: #1a1a2e; --brand-accent: #e74c3c; }
        body, .sidebar { font-family: 'Be Vietnam Pro', sans-serif; }
        .main-sidebar { background: var(--brand-dark) !important; }
        .brand-link   { background: #0f0f1e !important; border-bottom: 1px solid #333 !important; }
        .brand-text   { color: #fff !important; font-weight: 700; }
        .brand-text span { color: var(--brand-accent); }
        .nav-sidebar .nav-link { color: #c1c7d0 !important; }
        .nav-sidebar .nav-link:hover,
        .nav-sidebar .nav-link.active { color: #fff !important; background: rgba(231,76,60,0.2) !important; }
        .nav-sidebar .nav-link .nav-icon { color: var(--brand-accent) !important; }
        .content-header h1 { font-weight: 700; font-size: 1.4rem; }
        .card { border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.07); }
        .card-header { font-weight: 600; }
        .btn-primary { background: var(--brand-accent); border-color: var(--brand-accent); }
        .btn-primary:hover { background: #c0392b; border-color: #c0392b; }
        .small-box .icon { font-size: 3.5rem; }
        .navbar-dark { background: #16213e !important; }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-dark" style="background:#16213e;">
    <ul class="navbar-nav">
        <li class="nav-item"><a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a></li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a href="<?= APP_URL ?>/index.php?act=home" class="nav-link" target="_blank">
                <i class="fas fa-external-link-alt mr-1"></i>Xem trang web
            </a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
                <i class="fas fa-user-circle mr-1"></i><?= sanitize(currentUser()['name']) ?>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item text-danger" href="<?= APP_URL ?>/index.php?act=logout">
                    <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                </a>
            </div>
        </li>
    </ul>
</nav>

<!-- Sidebar -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background:var(--brand-dark)!important;">
    <a href="<?= APP_URL ?>/index.php?act=admin" class="brand-link">
        <i class="fas fa-car ml-3 mr-2" style="color:var(--brand-accent)"></i>
        <span class="brand-text">Car<span>Market</span></span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" data-accordion="false">
                <li class="nav-item">
                    <a href="<?= APP_URL ?>/index.php?act=admin" class="nav-link <?= ($_GET['act']??'')==='admin'?'active':'' ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-header" style="color:#666;">QUẢN LÝ</li>
                <li class="nav-item">
                    <a href="<?= APP_URL ?>/index.php?act=admin-cars" class="nav-link <?= str_contains($_GET['act']??'','car')?'active':'' ?>">
                        <i class="nav-icon fas fa-car"></i>
                        <p>Quản lý xe</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= APP_URL ?>/index.php?act=admin-users" class="nav-link <?= str_contains($_GET['act']??'','user')?'active':'' ?>">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Người dùng</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= APP_URL ?>/index.php?act=admin-brands" class="nav-link <?= str_contains($_GET['act']??'','brand')?'active':'' ?>">
                        <i class="nav-icon fas fa-tags"></i>
                        <p>Hãng xe</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= APP_URL ?>/index.php?act=admin-messages" class="nav-link <?= str_contains($_GET['act']??'','message')?'active':'' ?>">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>Tin nhắn</p>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</aside>

<!-- Content Wrapper -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <?php
            $flash = getFlash();
            if ($flash):
            ?>
            <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show mt-2">
                <?= sanitize($flash['message']) ?>
                <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
            </div>
            <?php endif; ?>
            <div class="row mb-2 mt-2">
                <div class="col-sm-6">
                    <h1 class="m-0"><?= $pageTitle ?? 'Dashboard' ?></h1>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">
