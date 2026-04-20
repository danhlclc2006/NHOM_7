<?php $pageTitle = 'Xe của tôi'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="font-weight-bold mb-0"><i class="fas fa-car mr-2 text-danger"></i>Xe của tôi</h4>
    <a href="<?= APP_URL ?>/index.php?act=form-add-car" class="btn btn-danger">
        <i class="fas fa-plus mr-1"></i>Đăng tin mới
    </a>
</div>

<?php if (empty($cars)): ?>
<div class="text-center py-5">
    <i class="fas fa-car fa-4x text-muted mb-3 d-block"></i>
    <h5 class="text-muted">Bạn chưa đăng tin xe nào</h5>
    <a href="<?= APP_URL ?>/index.php?act=form-add-car" class="btn btn-danger mt-2">Đăng tin ngay</a>
</div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>Xe</th>
                    <th>Giá</th>
                    <th>Năm</th>
                    <th>Trạng thái</th>
                    <th>Ngày đăng</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <?php if ($car['primary_image']): ?>
                        <img src="<?= UPLOAD_URL . sanitize($car['primary_image']) ?>"
                             style="width:60px;height:45px;object-fit:cover;border-radius:6px;margin-right:10px;">
                        <?php else: ?>
                        <div style="width:60px;height:45px;background:#f0f0f0;border-radius:6px;margin-right:10px;display:flex;align-items:center;justify-content:center;color:#ccc;">
                            <i class="fas fa-car"></i>
                        </div>
                        <?php endif; ?>
                        <div>
                            <div class="font-weight-bold" style="font-size:0.9rem;"><?= sanitize($car['title']) ?></div>
                            <small class="text-muted"><?= sanitize($car['brand_name']) ?></small>
                        </div>
                    </div>
                </td>
                <td class="text-danger font-weight-bold"><?= formatPrice((float)$car['price']) ?></td>
                <td><?= $car['year'] ?></td>
                <td><?= statusBadge($car['status']) ?></td>
                <td><small><?= date('d/m/Y', strtotime($car['created_at'])) ?></small></td>
                <td class="text-center">
                    <a href="<?= APP_URL ?>/index.php?act=detail&id=<?= $car['id'] ?>"
                       class="btn btn-sm btn-outline-info" title="Xem">
                        <i class="fas fa-eye"></i>
                    </a>
                    <a href="<?= APP_URL ?>/index.php?act=edit-car&id=<?= $car['id'] ?>"
                       class="btn btn-sm btn-outline-warning" title="Sửa">
                        <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= APP_URL ?>/index.php?act=delete-car&id=<?= $car['id'] ?>"
                       class="btn btn-sm btn-outline-danger" title="Xóa"
                       onclick="return confirm('Xóa xe này?')">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="d-flex justify-content-center mt-3">
    <?= paginationLinks($total, ITEMS_PER_PAGE, $page, APP_URL . '/index.php?act=my-cars') ?>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
