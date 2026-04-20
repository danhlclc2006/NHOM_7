<?php $pageTitle = sanitize($car['title']); require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.detail-img-main { width: 100%; height: 380px; object-fit: cover; border-radius: 12px; }
.thumb-list { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.thumb-list img { width: 80px; height: 60px; object-fit: cover; border-radius: 6px; cursor: pointer; border: 2px solid transparent; transition: border 0.2s; }
.thumb-list img:hover, .thumb-list img.active { border-color: #e74c3c; }
.info-table td { padding: 8px 12px; font-size: 0.92rem; }
.info-table tr:nth-child(even) { background: #f9f9f9; }
.info-label { font-weight: 600; color: #555; width: 160px; }
.price-big { color: #e74c3c; font-size: 1.8rem; font-weight: 700; }
.seller-card { background: #f8f9fa; border-radius: 10px; padding: 1.2rem; }
.no-image-lg { height: 380px; background: #f0f0f0; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #ccc; font-size: 5rem; }
</style>

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb bg-transparent p-0">
        <li class="breadcrumb-item"><a href="<?= APP_URL ?>/index.php?act=cars" class="text-danger">Xe đang bán</a></li>
        <li class="breadcrumb-item active"><?= sanitize($car['brand_name']) ?></li>
    </ol>
</nav>

<div class="row">
    <!-- Left: Images + Info -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <!-- Gallery -->
                <?php if (!empty($images)): ?>
                    <img id="mainImg" src="<?= UPLOAD_URL . sanitize($images[0]['image_path']) ?>"
                         alt="" class="detail-img-main" id="mainImg">
                    <div class="thumb-list">
                        <?php foreach ($images as $i => $img): ?>
                        <img src="<?= UPLOAD_URL . sanitize($img['image_path']) ?>"
                             onclick="document.getElementById('mainImg').src=this.src; document.querySelectorAll('.thumb-list img').forEach(x=>x.classList.remove('active')); this.classList.add('active');"
                             class="<?= $i===0?'active':'' ?>">
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-image-lg"><i class="fas fa-car"></i></div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Details -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-info-circle mr-2 text-danger"></i>Thông tin chi tiết
            </div>
            <div class="card-body p-0">
                <table class="table info-table mb-0">
                    <tr><td class="info-label">Hãng xe</td><td><?= sanitize($car['brand_name']) ?></td></tr>
                    <tr><td class="info-label">Năm sản xuất</td><td><?= $car['year'] ?></td></tr>
                    <tr><td class="info-label">Số km đã đi</td><td><?= formatNumber((int)$car['mileage']) ?> km</td></tr>
                    <tr><td class="info-label">Nhiên liệu</td><td><?= fuelLabel($car['fuel_type']) ?></td></tr>
                    <tr><td class="info-label">Hộp số</td><td><?= transmissionLabel($car['transmission']) ?></td></tr>
                    <tr><td class="info-label">Màu sắc</td><td><?= sanitize($car['color'] ?? 'Không rõ') ?></td></tr>
                    <tr><td class="info-label">Lượt xem</td><td><?= $car['views'] ?></td></tr>
                    <tr><td class="info-label">Ngày đăng</td><td><?= date('d/m/Y', strtotime($car['created_at'])) ?></td></tr>
                </table>
            </div>
        </div>

        <!-- Description -->
        <?php if ($car['description']): ?>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-file-alt mr-2 text-danger"></i>Mô tả xe
            </div>
            <div class="card-body">
                <p class="mb-0" style="line-height:1.8; color:#444;"><?= nl2br(sanitize($car['description'])) ?></p>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Right: Price + Seller + Contact -->
    <div class="col-lg-4">
        <!-- Price -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body text-center">
                <h2 class="price-big"><?= formatPrice((float)$car['price']) ?></h2>
                <p class="text-muted mb-0" style="font-size:0.85rem;"><?= number_format($car['price']) ?> VNĐ</p>
            </div>
        </div>

        <!-- Seller Info -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-user mr-2 text-danger"></i>Thông tin người bán
            </div>
            <div class="card-body">
                <div class="seller-card">
                    <p class="mb-2"><i class="fas fa-user-circle mr-2 text-secondary"></i>
                        <strong><?= sanitize($car['seller_name']) ?></strong>
                    </p>
                    <p class="mb-2"><i class="fas fa-phone mr-2 text-success"></i>
                        <a href="tel:<?= sanitize($car['seller_phone']) ?>" class="text-dark">
                            <?= sanitize($car['seller_phone'] ?? 'Không có') ?>
                        </a>
                    </p>
                    <p class="mb-0"><i class="fas fa-envelope mr-2 text-primary"></i>
                        <a href="mailto:<?= sanitize($car['seller_email']) ?>" class="text-dark">
                            <?= sanitize($car['seller_email']) ?>
                        </a>
                    </p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-paper-plane mr-2 text-danger"></i>Liên hệ người bán
            </div>
            <div class="card-body">
                <?php if (isLoggedIn()): ?>
                <form method="POST" action="<?= APP_URL ?>/index.php?act=send-message">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                    <div class="form-group">
                        <input type="text" name="sender_name" class="form-control form-control-sm"
                               placeholder="Họ tên của bạn" value="<?= sanitize(currentUser()['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="email" name="sender_email" class="form-control form-control-sm"
                               placeholder="Email" value="<?= sanitize(currentUser()['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <input type="text" name="sender_phone" class="form-control form-control-sm"
                               placeholder="Số điện thoại" value="<?= sanitize(currentUser()['phone'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <textarea name="content" class="form-control form-control-sm" rows="3"
                                  placeholder="Nội dung tin nhắn..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger btn-block btn-sm">
                        <i class="fas fa-paper-plane mr-1"></i>Gửi tin nhắn
                    </button>
                </form>
                <?php else: ?>
                <p class="text-center text-muted">
                    <a href="<?= APP_URL ?>/index.php?act=login" class="text-danger font-weight-bold">Đăng nhập</a>
                    để liên hệ người bán
                </p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
