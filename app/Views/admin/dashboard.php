<?php
$pageTitle = 'Bảng điều khiển';
$pageSubtitle = 'Tổng quan tình hình tour, booking và doanh thu';
// Format tiền tệ cho gọn
$revenue = isset($stats['month_revenue']) ? number_format($stats['month_revenue']) : '0';
?>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Tổng quan</h1>
            <p class="text-muted mb-0 small">Chào mừng trở lại, đây là báo cáo hôm nay của bạn.</p>
        </div>
        <div>
            <a href="<?= BASE_URL ?>?act=admin-tours-create" class="btn btn-primary shadow-sm">
                <i class="bi bi-plus-circle me-1"></i> Tạo Tour mới
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted small fw-bold mb-1">Tổng số Tour</div>
                            <div class="fs-3 fw-bold text-dark"><?= number_format($stats['tours_count'] ?? 0) ?></div>
                            <div class="small text-success mt-1">
                                <i class="bi bi-arrow-up-short"></i> <span class="fw-semibold">Đang hoạt động</span>
                            </div>
                        </div>
                        <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-map fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted small fw-bold mb-1">Booking mới</div>
                            <div class="fs-3 fw-bold text-dark"><?= number_format($stats['today_bookings'] ?? 0) ?></div>
                            <div class="small text-muted mt-1">Hôm nay</div>
                        </div>
                        <div class="bg-info-subtle text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-ticket-perforated fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted small fw-bold mb-1">Đoàn khởi hành</div>
                            <div class="fs-3 fw-bold text-dark"><?= number_format($stats['active_departures'] ?? 0) ?></div>
                            <div class="small text-muted mt-1">Đang đi tour</div>
                        </div>
                        <div class="bg-warning-subtle text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-bus-front fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="text-uppercase text-muted small fw-bold mb-1">Doanh thu tháng</div>
                            <div class="fs-3 fw-bold text-success"><?= $revenue ?> <span class="fs-6 text-muted fw-normal">₫</span></div>
                            <div class="small text-success mt-1">
                                <i class="bi bi-graph-up-arrow"></i> Tăng trưởng
                            </div>
                        </div>
                        <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 56px; height: 56px;">
                            <i class="bi bi-currency-dollar fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-gray-800"><i class="bi bi-receipt me-2 text-primary"></i>Booking gần đây</h5>
                    <a href="<?= BASE_URL ?>?act=admin-bookings" class="btn btn-sm btn-light text-primary fw-medium">Xem tất cả</a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small text-uppercase">
                                <tr>
                                    <th class="ps-4">Mã vé</th>
                                    <th>Khách hàng</th>
                                    <th>Tour đăng ký</th>
                                    <th>Trạng thái</th>
                                    <th class="text-end pe-4">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recentBookings)): ?>
                                    <?php foreach ($recentBookings as $b):
                                        $status = $b['status'] ?? 'Chờ';
                                        // Logic màu badge
                                        $badgeClass = match ($status) {
                                            'Hoàn tất', 'Completed' => 'bg-success-subtle text-success border-success-subtle',
                                            'Đã cọc', 'Paid' => 'bg-primary-subtle text-primary border-primary-subtle',
                                            'Hủy', 'Cancelled' => 'bg-danger-subtle text-danger border-danger-subtle',
                                            default => 'bg-secondary-subtle text-secondary border-secondary-subtle'
                                        };
                                    ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-primary">#<?= $b['booking_id'] ?></td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= htmlspecialchars($b['contact_name'] ?? 'Khách lẻ') ?></div>
                                                <div class="small text-muted"><?= htmlspecialchars($b['phone'] ?? '') ?></div>
                                            </td>
                                            <td>
                                                <div class="text-truncate" style="max-width: 200px;" title="<?= htmlspecialchars($b['tour_name'] ?? '') ?>">
                                                    <?= htmlspecialchars($b['tour_name'] ?? '-') ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge rounded-pill border <?= $badgeClass ?> px-3 py-1">
                                                    <?= $status ?>
                                                </span>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="#" class="btn btn-sm btn-light text-muted"><i class="bi bi-three-dots-vertical"></i></a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                            Chưa có booking nào mới.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold text-gray-800"><i class="bi bi-bar-chart-line me-2 text-success"></i>Biểu đồ doanh thu</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center bg-light rounded-3 border border-dashed" style="height: 280px;">
                        <div class="text-center text-muted">
                            <i class="bi bi-pie-chart fs-1 mb-2 d-block"></i>
                            <p class="mb-0">Khu vực hiển thị biểu đồ (Cần tích hợp Chart.js)</p>
                            <small>(Dữ liệu mẫu: Doanh thu 7 ngày gần nhất)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold text-gray-800"><i class="bi bi-calendar-week me-2 text-warning"></i>Sắp khởi hành</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($upcomingTours)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($upcomingTours as $t): ?>
                                <div class="list-group-item px-4 py-3 border-bottom-0">
                                    <div class="d-flex w-100 justify-content-between align-items-start mb-1">
                                        <div class="text-truncate me-2 fw-semibold text-dark" style="max-width: 70%;">
                                            <?= htmlspecialchars($t['tour_name'] ?? 'Tour chưa đặt tên') ?>
                                        </div>
                                        <small class="text-danger fw-bold text-nowrap">
                                            <?= isset($t['start_date']) ? date('d/m', strtotime($t['start_date'])) : '' ?>
                                        </small>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge bg-light text-dark border">
                                            <?= htmlspecialchars($t['tour_type'] ?? 'General') ?>
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-people-fill me-1"></i> <?= $t['joined_count'] ?? 0 ?> khách
                                        </small>
                                    </div>
                                    <div class="mt-2">
                                        <a href="<?= BASE_URL ?>?act=admin-tours-edit&id=<?= $t['tour_id'] ?? 0 ?>" class="btn btn-sm btn-outline-primary w-100">Chi tiết</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted">
                            <small>Không có lịch khởi hành sắp tới.</small>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="card-footer bg-white border-0 text-center py-3">
                    <a href="#" class="text-decoration-none small fw-bold">Xem lịch trình đầy đủ <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="card-title mb-0 fw-bold text-gray-800"><i class="bi bi-bell me-2 text-secondary"></i>Hoạt động hệ thống</h5>
                </div>
                <div class="card-body p-0">
                    <?php if (!empty($logs)): ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($logs as $l): ?>
                                <div class="list-group-item px-4 py-2">
                                    <div class="d-flex w-100 justify-content-between">
                                        <small class="fw-semibold text-dark"><?= htmlspecialchars($l['title'] ?? 'System Log') ?></small>
                                        <small class="text-muted" style="font-size: 0.75rem;"><?= isset($l['created_at']) ? date('H:i', strtotime($l['created_at'])) : '' ?></small>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4 text-muted small">
                            Không có thông báo mới.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>