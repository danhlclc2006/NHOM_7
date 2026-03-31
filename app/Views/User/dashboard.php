<div class="container-fluid p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h3 class="text-gray-800 m-0 fw-bold">Lịch Dẫn Tour Của Tôi</h3>
        <span class="badge bg-primary rounded-pill px-3 py-2">
            Tổng: <?= count($schedules) ?> tour
        </span>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-warning mb-4 shadow-sm border-0 border-start border-warning border-4">
            <?= $_SESSION['flash'];
            unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>

    <?php if (empty($schedules)): ?>
        <div class="text-center py-5 text-muted bg-white rounded shadow-sm">
            <i class="bi bi-calendar-x display-1 text-muted opacity-25"></i>
            <p class="mt-3 fs-5">Bạn chưa được phân công tour nào.</p>
        </div>
    <?php else: ?>

        <div class="row g-4">
            <?php foreach ($schedules as $s): ?>
                <div class="col-12 col-md-6 col-xl-4">
                    <div class="card h-100 border-0 shadow-sm hover-shadow transition-all">
                        <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                            <div class="d-flex justify-content-between align-items-start">
                                <h5 class="card-title text-primary fw-bold mb-0 text-truncate-2"
                                    style="min-height: 48px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($s['tour_name']) ?>
                                </h5>
                            </div>
                        </div>

                        <div class="card-body px-4 pt-3">
                            <div class="d-flex align-items-center mb-2 text-muted">
                                <i class="bi bi-calendar-check me-2 text-success"></i>
                                <span class="fw-semibold text-dark">
                                    <?= date('d/m/Y', strtotime($s['start_date'])) ?>
                                </span>
                                <i class="bi bi-arrow-right mx-2 small"></i>
                                <span>
                                    <?= isset($s['end_date']) ? date('d/m/Y', strtotime($s['end_date'])) : '...' ?>
                                </span>
                            </div>

                            <div class="d-flex align-items-start mb-3 text-muted">
                                <i class="bi bi-geo-alt-fill me-2 text-danger mt-1"></i>
                                <span>
                                    <?= htmlspecialchars($s['gathering_point'] ?? 'Chưa cập nhật điểm đón') ?>
                                </span>
                            </div>
                        </div>

                        <div class="card-footer bg-white border-0 px-4 pb-4 pt-0">
                            <a href="<?= BASE_URL ?>?act=hdv-tour-detail&id=<?= $s['assign_id'] ?>"
                                class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                <i class="bi bi-gear-fill me-2"></i>Điều hành Tour
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
        transition: all 0.3s ease;
    }

    .transition-all {
        transition: all 0.3s ease;
    }
</style>