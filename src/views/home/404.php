<?php $pageTitle = '404 - Không tìm thấy'; require_once __DIR__ . '/../layouts/header.php'; ?>
<div class="text-center py-5">
    <div style="font-size:6rem; color:#e74c3c; font-weight:900;">404</div>
    <h3 class="text-muted">Trang không tồn tại</h3>
    <p class="text-muted">Xin lỗi, trang bạn tìm không tồn tại hoặc đã bị xóa.</p>
    <a href="<?= APP_URL ?>/index.php?act=home" class="btn btn-danger mt-2">
        <i class="fas fa-home mr-1"></i>Về trang chủ
    </a>
</div>
<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
