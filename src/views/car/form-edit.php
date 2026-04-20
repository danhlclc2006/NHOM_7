<?php $pageTitle = 'Chỉnh sửa xe'; require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 font-weight-bold"><i class="fas fa-edit mr-2 text-danger"></i>Chỉnh sửa thông tin xe</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="<?= APP_URL ?>/index.php?act=update-car" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $car['id'] ?>">

                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" value="<?= sanitize($car['title']) ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Hãng xe</label>
                            <select name="brand_id" class="form-control" required>
                                <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= $car['brand_id']==$b['id']?'selected':'' ?>>
                                    <?= sanitize($b['name']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Giá (VNĐ)</label>
                            <input type="number" name="price" class="form-control" value="<?= $car['price'] ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Năm sản xuất</label>
                            <select name="year" class="form-control">
                                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                                <option value="<?= $y ?>" <?= $car['year']==$y?'selected':'' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Số km</label>
                            <input type="number" name="mileage" class="form-control" value="<?= $car['mileage'] ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Nhiên liệu</label>
                            <select name="fuel_type" class="form-control">
                                <?php foreach (['xang'=>'Xăng','dau'=>'Dầu','dien'=>'Điện','hybrid'=>'Hybrid'] as $v=>$l): ?>
                                <option value="<?= $v ?>" <?= $car['fuel_type']===$v?'selected':'' ?>><?= $l ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Hộp số</label>
                            <select name="transmission" class="form-control">
                                <option value="tu_dong" <?= $car['transmission']==='tu_dong'?'selected':'' ?>>Tự động</option>
                                <option value="so_san"  <?= $car['transmission']==='so_san'?'selected':'' ?>>Số sàn</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Màu sắc</label>
                            <input type="text" name="color" class="form-control" value="<?= sanitize($car['color'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Mô tả</label>
                    <textarea name="description" class="form-control" rows="4"><?= sanitize($car['description'] ?? '') ?></textarea>
                </div>

                <!-- Current Images -->
                <?php if (!empty($images)): ?>
                <div class="form-group">
                    <label class="font-weight-bold">Ảnh hiện tại</label>
                    <div class="d-flex flex-wrap" style="gap:8px;">
                        <?php foreach ($images as $img): ?>
                        <img src="<?= UPLOAD_URL . sanitize($img['image_path']) ?>"
                             style="width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid <?= $img['is_primary']?'#e74c3c':'#dee2e6' ?>;">
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="form-group">
                    <label class="font-weight-bold">Thêm ảnh mới</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" accept="image/*" multiple>
                        <label class="custom-file-label">Chọn ảnh mới (tùy chọn)</label>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="<?= APP_URL ?>/index.php?act=my-cars" class="btn btn-outline-secondary mr-2">Hủy</a>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-save mr-1"></i>Lưu thay đổi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
