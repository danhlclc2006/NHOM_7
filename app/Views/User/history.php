<div class="container-fluid p-4">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h3 class="text-gray-800 m-0 fw-bold"><?= $pageTitle ?></h3>
            <p class="text-muted mb-0 small"><?= $pageSubtitle ?></p>
        </div>
        <span class="badge bg-secondary rounded-pill px-3 py-2">
            Tổng số: <?= count($history) ?> tour
        </span>
    </div>

    <?php if (empty($history)): ?>
        <div class="text-center py-5 text-muted bg-white rounded shadow-sm border border-light">
            <i class="bi bi-clock-history display-1 text-muted opacity-25"></i>
            <p class="mt-3 fs-5">Bạn chưa có lịch sử dẫn tour nào.</p>
        </div>
    <?php else: ?>

        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Tên Tour</th>
                                <th>Thời gian</th>
                                <th>Địa điểm đón</th>
                                <th>Hoàn thành lúc</th>
                                <th class="text-end pe-4">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($history as $h): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-success-subtle text-success rounded p-2 me-3">
                                                <i class="bi bi-check-circle-fill"></i>
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark"><?= htmlspecialchars($h['tour_name']) ?></div>
                                                <small class="text-muted">Mã tour: #<?= $h['tour_id'] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column small">
                                            <span class="fw-semibold text-primary"><?= date('d/m/Y', strtotime($h['start_date'])) ?></span>
                                            <span class="text-muted">đến <?= date('d/m/Y', strtotime($h['end_date'])) ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="small text-muted"><i class="bi bi-geo-alt me-1"></i><?= htmlspecialchars($h['gathering_point'] ?? 'N/A') ?></span>
                                    </td>
                                    <td>
                                        <?php if (!empty($h['actual_end_time'])): ?>
                                            <span class="badge bg-light text-secondary border">
                                                <?= date('H:i d/m/Y', strtotime($h['actual_end_time'])) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="<?= BASE_URL ?>?act=hdv-tour-detail&id=<?= $h['assign_id'] ?>" 
                                           class="btn btn-sm btn-outline-secondary">
                                            <i class="bi bi-eye-fill me-1"></i> Xem lại
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>