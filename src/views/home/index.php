<?php $pageTitle = 'Xe đang bán'; require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
.car-card { border-radius: 12px; overflow: hidden; transition: transform 0.2s, box-shadow 0.2s; border: none; }
.car-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important; }
.car-img-wrap { height: 200px; overflow: hidden; background: #f0f0f0; }
.car-img-wrap img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s; }
.car-card:hover .car-img-wrap img { transform: scale(1.05); }
.price-tag { color: #e74c3c; font-weight: 700; font-size: 1.15rem; }
.car-meta span { font-size: 0.82rem; color: #888; margin-right: 10px; }
.filter-card { border-radius: 12px; border: none; box-shadow: 0 2px 12px rgba(0,0,0,0.08); }
.hero-banner {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
    padding: 3rem 0; margin: -1.5rem -15px 2rem; border-radius: 0 0 20px 20px;
}
.hero-banner h1 { font-weight: 700; font-size: 2.2rem; }
.hero-banner .subtitle { opacity: 0.7; font-size: 1rem; }
.no-image { height: 200px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #bbb; font-size: 3rem; }
.badge-fuel { background: #eaf4fb; color: #2980b9; font-size: 0.78rem; padding: 3px 8px; border-radius: 20px; }
.badge-km   { background: #fef9e7; color: #f39c12; font-size: 0.78rem; padding: 3px 8px; border-radius: 20px; }
</style>

<!-- Hero -->
<div class="hero-banner text-white text-center">
    <h1><i class="fas fa-car mr-2" style="color:#e74c3c"></i>Mua Bán Xe Uy Tín</h1>
    <p class="subtitle">Hàng nghìn xe chính chủ, giá tốt nhất Việt Nam</p>
    <div class="row justify-content-center mt-3">
        <div class="col-md-6">
            <form method="GET" action="<?= APP_URL ?>/index.php" class="input-group">
                <input type="hidden" name="act" value="cars">
                <input type="text" name="keyword" class="form-control form-control-lg"
                       placeholder="Tìm xe theo tên, mô tả..."
                       value="<?= sanitize($filters['keyword'] ?? '') ?>">
                <div class="input-group-append">
                    <button class="btn btn-danger px-4"><i class="fas fa-search"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <!-- Sidebar Filter -->
    <div class="col-md-3">
        <div class="card filter-card mb-4">
            <div class="card-header bg-white font-weight-bold">
                <i class="fas fa-filter mr-2 text-danger"></i>Lọc xe
            </div>
            <div class="card-body">
                <form method="GET" action="<?= APP_URL ?>/index.php">
                    <input type="hidden" name="act" value="cars">
                    <?php if (!empty($filters['keyword'])): ?>
                        <input type="hidden" name="keyword" value="<?= sanitize($filters['keyword']) ?>">
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="small font-weight-bold">Hãng xe</label>
                        <select name="brand_id" class="form-control form-control-sm">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($brands as $b): ?>
                            <option value="<?= $b['id'] ?>" <?= ($filters['brand_id']==$b['id'])?'selected':'' ?>>
                                <?= sanitize($b['name']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Năm sản xuất</label>
                        <select name="year" class="form-control form-control-sm">
                            <option value="">-- Tất cả --</option>
                            <?php foreach ($years as $y): ?>
                            <option value="<?= $y ?>" <?= ($filters['year']==$y)?'selected':'' ?>><?= $y ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Giá từ (triệu)</label>
                        <input type="number" name="min_price" class="form-control form-control-sm"
                               placeholder="VD: 200000000"
                               value="<?= $filters['min_price'] ?: '' ?>">
                    </div>
                    <div class="form-group">
                        <label class="small font-weight-bold">Giá đến (triệu)</label>
                        <input type="number" name="max_price" class="form-control form-control-sm"
                               placeholder="VD: 1000000000"
                               value="<?= $filters['max_price'] ?: '' ?>">
                    </div>
                    <button type="submit" class="btn btn-danger btn-block btn-sm">
                        <i class="fas fa-search mr-1"></i>Tìm kiếm
                    </button>
                    <a href="<?= APP_URL ?>/index.php?act=cars" class="btn btn-outline-secondary btn-block btn-sm mt-1">
                        Xóa bộ lọc
                    </a>
                </form>
            </div>
        </div>
    </div>

    <!-- Car Grid -->
    <div class="col-md-9">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <p class="mb-0 text-muted">Tìm thấy <strong><?= $total ?></strong> xe</p>
            <?php if (isLoggedIn()): ?>
            <a href="<?= APP_URL ?>/index.php?act=form-add-car" class="btn btn-danger btn-sm">
                <i class="fas fa-plus mr-1"></i>Đăng tin bán xe
            </a>
            <?php endif; ?>
        </div>

        <?php if (empty($cars)): ?>
        <div class="text-center py-5">
            <i class="fas fa-car fa-4x text-muted mb-3 d-block"></i>
            <h5 class="text-muted">Không tìm thấy xe nào</h5>
            <a href="<?= APP_URL ?>/index.php?act=cars" class="btn btn-outline-danger mt-2">Xem tất cả xe</a>
        </div>
        <?php else: ?>
        <div class="row">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-4 mb-4">
                <div class="card car-card shadow-sm h-100">
                    <a href="<?= APP_URL ?>/index.php?act=detail&id=<?= $car['id'] ?>">
                        <?php if ($car['primary_image']): ?>
                        <div class="car-img-wrap">
                            <img src="<?= UPLOAD_URL . sanitize($car['primary_image']) ?>" alt="<?= sanitize($car['title']) ?>">
                        </div>
                        <?php else: ?>
                        <div class="no-image"><i class="fas fa-car"></i></div>
                        <?php endif; ?>
                    </a>
                    <div class="card-body pb-2">
                        <a href="<?= APP_URL ?>/index.php?act=detail&id=<?= $car['id'] ?>" class="text-dark text-decoration-none">
                            <h6 class="font-weight-bold mb-1" style="line-height:1.3;"><?= sanitize($car['title']) ?></h6>
                        </a>
                        <div class="price-tag mb-2"><?= formatPrice((float)$car['price']) ?></div>
                        <div class="car-meta mb-2">
                            <span><i class="fas fa-calendar mr-1"></i><?= $car['year'] ?></span>
                            <span><i class="fas fa-road mr-1"></i><?= formatNumber((int)$car['mileage']) ?> km</span>
                        </div>
                        <div>
                            <span class="badge-fuel mr-1"><?= fuelLabel($car['fuel_type']) ?></span>
                            <span class="badge-km"><?= transmissionLabel($car['transmission']) ?></span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-2">
                        <small class="text-muted">
                            <i class="fas fa-user mr-1"></i><?= sanitize($car['seller_name']) ?>
                            &nbsp;|&nbsp;<i class="fas fa-eye mr-1"></i><?= $car['views'] ?>
                        </small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-2">
            <?= paginationLinks($total, ITEMS_PER_PAGE, $page, APP_URL . '/index.php?act=cars' . (!empty($filters['keyword']) ? '&keyword='.urlencode($filters['keyword']) : '') . ($filters['brand_id'] ? '&brand_id='.$filters['brand_id'] : '')) ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
