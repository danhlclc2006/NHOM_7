<?php $pageTitle = 'Đăng tin bán xe'; require_once __DIR__ . '/../layouts/header.php';
$errors   = $_SESSION['form_errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
unset($_SESSION['form_errors'], $_SESSION['form_data']);
?>
<div class="row justify-content-center">
<div class="col-lg-8">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0 font-weight-bold"><i class="fas fa-plus-circle mr-2 text-danger"></i>Đăng tin bán xe</h5>
        </div>
        <div class="card-body">
            <?php if ($errors): ?>
            <div class="alert alert-danger py-2">
                <?php foreach ($errors as $e): ?><div>• <?= sanitize($e) ?></div><?php endforeach; ?>
            </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/index.php?act=add-car" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label class="font-weight-bold">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                   placeholder="VD: Toyota Camry 2.5Q 2022 - Trắng - Ít đi"
                                   value="<?= sanitize($formData['title'] ?? '') ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Hãng xe <span class="text-danger">*</span></label>
                            <select name="brand_id" class="form-control" required>
                                <option value="">-- Chọn hãng --</option>
                                <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= ($formData['brand_id']??'')==$b['id']?'selected':'' ?>>
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
                            <label class="font-weight-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" name="price" class="form-control"
                                   placeholder="VD: 850000000"
                                   value="<?= $formData['price'] ?? '' ?>" required min="1">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Năm sản xuất</label>
                            <select name="year" class="form-control">
                                <?php for ($y = date('Y'); $y >= 2000; $y--): ?>
                                <option value="<?= $y ?>" <?= ($formData['year']??date('Y'))==$y?'selected':'' ?>><?= $y ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Số km đã đi</label>
                            <input type="number" name="mileage" class="form-control"
                                   placeholder="VD: 25000"
                                   value="<?= $formData['mileage'] ?? 0 ?>" min="0">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Nhiên liệu</label>
                            <select name="fuel_type" class="form-control">
                                <option value="xang"   <?= ($formData['fuel_type']??'xang')==='xang'?'selected':'' ?>>Xăng</option>
                                <option value="dau"    <?= ($formData['fuel_type']??'')==='dau'?'selected':'' ?>>Dầu</option>
                                <option value="dien"   <?= ($formData['fuel_type']??'')==='dien'?'selected':'' ?>>Điện</option>
                                <option value="hybrid" <?= ($formData['fuel_type']??'')==='hybrid'?'selected':'' ?>>Hybrid</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Hộp số</label>
                            <select name="transmission" class="form-control">
                                <option value="tu_dong" <?= ($formData['transmission']??'tu_dong')==='tu_dong'?'selected':'' ?>>Tự động</option>
                                <option value="so_san"  <?= ($formData['transmission']??'')==='so_san'?'selected':'' ?>>Số sàn</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="font-weight-bold">Màu sắc</label>
                            <input type="text" name="color" class="form-control"
                                   placeholder="VD: Trắng, Đen, Đỏ..."
                                   value="<?= sanitize($formData['color'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Mô tả chi tiết</label>
                    <textarea name="description" class="form-control" rows="4"
                              placeholder="Mô tả tình trạng xe, lịch sử bảo dưỡng, ưu điểm..."><?= sanitize($formData['description'] ?? '') ?></textarea>
                </div>

                <div class="form-group">
                    <label class="font-weight-bold">Hình ảnh xe</label>
                    <div class="custom-file">
                        <input type="file" name="images[]" class="custom-file-input" id="carImages"
                               accept="image/*" multiple onchange="previewImages(this)">
                        <label class="custom-file-label" for="carImages">Chọn ảnh (có thể chọn nhiều)</label>
                    </div>
                    <small class="text-muted">Ảnh đầu tiên sẽ là ảnh đại diện. Tối đa 5MB/ảnh.</small>
                    <div id="imgPreview" class="d-flex flex-wrap mt-2" style="gap:8px;"></div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="<?= APP_URL ?>/index.php?act=my-cars" class="btn btn-outline-secondary mr-2">Hủy</a>
                    <button type="submit" class="btn btn-danger px-4">
                        <i class="fas fa-paper-plane mr-1"></i>Đăng tin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

<?php $extraScripts = <<<JS
<script>
function previewImages(input) {
    const preview = document.getElementById('imgPreview');
    preview.innerHTML = '';
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'width:80px;height:60px;object-fit:cover;border-radius:6px;border:2px solid #e74c3c;';
            preview.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}
</script>
JS; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
