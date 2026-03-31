<div class="container-fluid p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">Điều hành Tour: <?= htmlspecialchars($assign['tour_name'] ?? 'Tour') ?></h1>
            <p class="text-muted mb-0 small">
                <i class="bi bi-clock-history"></i> Trạng thái:
                <?php
                $statusColor = match ($assign['status'] ?? '') {
                    'Sẵn sàng' => 'success',
                    'Đang đi' => 'primary',
                    'Hoàn tất' => 'secondary',
                    default => 'warning text-dark'
                };
                ?>
                <span class="badge bg-<?= $statusColor ?>"><?= $assign['status'] ?? 'Mở bán' ?></span>
            </p>
        </div>
        <a href="<?= BASE_URL ?>?act=hdv-dashboard" class="btn btn-outline-secondary btn-sm shadow-sm">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <?php if (!empty($_SESSION['flash'])): ?>
        <div class="alert alert-success border-0 shadow-sm border-start border-success border-4 animate__animated animate__fadeIn">
            <i class="bi bi-check-circle-fill me-2"></i><?= $_SESSION['flash'];
                                                            unset($_SESSION['flash']); ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($_SESSION['error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm border-start border-danger border-4">
            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= $_SESSION['error'];
                                                                    unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-primary text-uppercase">Tiến độ chuyến đi</h6>
                </div>
                <div class="card-body">
                    <div class="stepper">

                        <?php
                        $isStarted = !empty($assign['actual_start_time']);
                        $step1Class = $isStarted ? 'completed' : 'active';

                        // Biến kiểm tra quyền thao tác (Chỉ cho phép khi Sẵn sàng hoặc Đang đi)
                        $canAction = ($assign['status'] === 'Sẵn sàng' || $assign['status'] === 'Đang đi');
                        $disabledAttr = $canAction ? '' : 'disabled';
                        $tooltip = $canAction ? '' : 'title="Chờ Admin cấp lệnh"';
                        ?>
                        <div class="step <?= $step1Class ?>">
                            <div class="step-icon"><i class="bi bi-bus-front-fill"></i></div>
                            <div class="step-content pb-4">
                                <h5 class="fw-bold">1. Đón khách & Khởi hành</h5>

                                <?php if ($isStarted): ?>
                                    <div class="text-success small mb-2">
                                        <i class="bi bi-check-circle-fill"></i> Đã xuất phát lúc <?= date('H:i d/m/Y', strtotime($assign['actual_start_time'])) ?>
                                    </div>
                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#checkinList1">
                                        Xem lại danh sách đón <i class="bi bi-chevron-down"></i>
                                    </button>
                                <?php else: ?>
                                    <p class="text-muted small">Vui lòng điểm danh khách hàng trước khi xe lăn bánh.</p>
                                <?php endif; ?>

                                <div class="<?= $isStarted ? 'collapse' : '' ?> mt-3" id="checkinList1">
                                    <div class="card border-primary border-2 shadow-sm">
                                        <div class="card-header bg-primary-subtle text-primary py-2 small fw-bold">
                                            Danh sách đoàn (<?= count($customers) ?> khách)
                                        </div>
                                        <div class="card-body p-0">
                                            <table class="table table-hover align-middle mb-0 small">
                                                <thead class="bg-light text-muted text-uppercase">
                                                    <tr>
                                                        <th class="ps-3">Họ tên / SĐT</th>
                                                        <th class="text" style="width: 30%">Lưu ý đặc biệt</th>
                                                        <th class="text-end pe-3">Điểm danh</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (!empty($customers)): foreach ($customers as $cus): ?>
                                                            <tr class="<?= $cus['check_in'] == 1 ? 'bg-success-subtle' : '' ?>">
                                                                <td class="ps-3">
                                                                    <strong><?= htmlspecialchars($cus['full_name']) ?></strong><br>
                                                                    <span class="text-muted"><?= $cus['phone'] ?? '-' ?></span>
                                                                </td>

                                                                <td class="text-wrap">
                                                                    <?php if (!empty($cus['note'])): ?>
                                                                        <div class=" fw-bold d-flex align-items-start">
                                                                            <span><?= htmlspecialchars($cus['note']) ?></span>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <span class="text-muted fw-light">-</span>
                                                                    <?php endif; ?>
                                                                </td>
                                                                <td class="text-end pe-3">
                                                                    <form action="<?= BASE_URL ?>?act=hdv-customer-update" method="POST">
                                                                        <input type="hidden" name="type" value="checkin">
                                                                        <input type="hidden" name="customer_id" value="<?= $cus['customer_id'] ?>">
                                                                        <input type="hidden" name="assign_id" value="<?= $assign['departure_id'] ?>">

                                                                        <?php if ($cus['check_in'] == 1): ?>
                                                                            <input type="hidden" name="value" value="0">
                                                                            <button class="btn btn-sm btn-success fw-bold" <?= $disabledAttr ?> <?= $tooltip ?>>
                                                                                <i class="bi bi-check"></i> Lên xe
                                                                            </button>
                                                                        <?php else: ?>
                                                                            <input type="hidden" name="value" value="1">
                                                                            <button class="btn btn-sm btn-outline-secondary" <?= $disabledAttr ?> <?= $tooltip ?>>
                                                                                Chưa đến
                                                                            </button>
                                                                        <?php endif; ?>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                    <?php endforeach;
                                                    endif; ?>
                                                </tbody>
                                            </table>
                                        </div>

                                        <?php if (!$isStarted): ?>
                                            <div class="card-footer bg-white text-end">
                                                <?php if ($assign['status'] === 'Sẵn sàng'): ?>
                                                    <form action="<?= BASE_URL ?>?act=hdv-tour-start" method="POST" onsubmit="return confirm('Xác nhận đoàn đã đủ và bắt đầu khởi hành?');">
                                                        <input type="hidden" name="departure_id" value="<?= $assign['departure_id'] ?>">
                                                        <button class="btn btn-primary fw-bold pulse-anim">
                                                            <i class="bi bi-send-fill me-2"></i>XÁC NHẬN KHỞI HÀNH
                                                        </button>
                                                    </form>

                                                <?php elseif ($assign['status'] === 'Mở bán'): ?>
                                                    <div class="alert alert-warning mb-0 border-0 py-2 d-flex align-items-center justify-content-end">
                                                        <span class="me-3 small"><i class="bi bi-hourglass-split"></i> Chờ Admin cấp lệnh...</span>
                                                        <button class="btn btn-secondary btn-sm disabled" disabled>
                                                            Bắt đầu <i class="bi bi-lock-fill"></i>
                                                        </button>
                                                    </div>

                                                <?php else: ?>
                                                    <div class="text-muted small">Tour không khả dụng để bắt đầu.</div>
                                                <?php endif; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php
                        $allowStartNext = true;
                        $allSchedulesCompleted = true;

                        if (!empty($schedules)):
                            foreach ($schedules as $index => $sch):
                                $status = $sch['progress_status'] ?? 'Pending';
                                if ($status !== 'Completed') $allSchedulesCompleted = false;

                                // Xác định quyền hiển thị nút check-in
                                $canShowCheckin = false;
                                if ($status == 'Pending') {
                                    if ($isStarted && $allowStartNext) {
                                        $canShowCheckin = true;
                                        $allowStartNext = false;
                                    } else {
                                        $canShowCheckin = false;
                                    }
                                } elseif ($status == 'In Progress') {
                                    $allowStartNext = false;
                                } elseif ($status == 'Completed') {
                                    $allowStartNext = true;
                                }

                                $stepClass = '';
                                if ($status == 'Completed') $stepClass = 'completed';
                                elseif ($status == 'In Progress') $stepClass = 'active';
                                elseif ($canShowCheckin) $stepClass = 'active';
                                else $stepClass = 'disabled';
                        ?>
                                <div class="step <?= $stepClass ?>">
                                    <div class="step-icon"><?= $index + 2 ?></div>
                                    <div class="step-content pb-4">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="fw-bold mb-1">Ngày <?= $sch['day_number'] ?>: <?= htmlspecialchars($sch['location']) ?></h6>
                                                <div class="small text-muted"><?= htmlspecialchars($sch['description']) ?></div>
                                            </div>

                                            <div>
                                                <?php if ($canShowCheckin): ?>
                                                    <form action="<?= BASE_URL ?>?act=hdv-schedule-start" method="POST" enctype="multipart/form-data" class="d-flex align-items-center gap-2">
                                                        <input type="hidden" name="departure_id" value="<?= $assign['departure_id'] ?>">
                                                        <input type="hidden" name="schedule_id" value="<?= $sch['schedule_id'] ?>">

                                                        <div class="input-group input-group-sm" style="max-width: 200px;">
                                                            <input type="file" name="schedule_image" class="form-control" accept="image/*">
                                                        </div>

                                                        <button class="btn btn-sm btn-outline-primary fw-bold pulse-anim text-nowrap">
                                                            Check-in <i class="bi bi-camera-fill"></i>
                                                        </button>
                                                    </form>

                                                <?php elseif ($status == 'In Progress'): ?>
                                                    <form action="<?= BASE_URL ?>?act=hdv-schedule-end" method="POST" onsubmit="return confirm('Hoàn thành hoạt động này?');">
                                                        <input type="hidden" name="departure_id" value="<?= $assign['departure_id'] ?>">
                                                        <input type="hidden" name="schedule_id" value="<?= $sch['schedule_id'] ?>">
                                                        <button class="btn btn-sm btn-success fw-bold">Hoàn tất <i class="bi bi-check-lg"></i></button>
                                                    </form>

                                                <?php elseif ($status == 'Completed'): ?>
                                                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-all"></i> Xong</span>

                                                <?php else: ?>
                                                    <span class="text-muted small fst-italic"><i class="bi bi-lock"></i> Chờ...</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                        <?php endforeach;
                        endif; ?>

                        <?php
                        $finalStepClass = ($isStarted && $allSchedulesCompleted) ? 'active' : 'disabled';
                        if ($assign['status'] === 'Hoàn tất') $finalStepClass = 'completed';
                        ?>
                        <div class="step <?= $finalStepClass ?>">
                            <div class="step-icon"><i class="bi bi-house-door-fill"></i></div>
                            <div class="step-content">
                                <h5 class="fw-bold">Trả khách & Kết thúc</h5>
                                <?php if ($assign['status'] === 'Hoàn tất'): ?>
                                    <div class="alert alert-success mb-0">
                                        <i class="bi bi-archive-fill me-2"></i> Tour đã kết thúc và lưu kho.
                                    </div>
                                <?php elseif ($isStarted && $allSchedulesCompleted): ?>
                                    <p class="mb-3 text-muted small">Xác nhận đã trả khách an toàn để đóng hồ sơ chuyến đi.</p>
                                    <form action="<?= BASE_URL ?>?act=hdv-tour-finish" method="POST" onsubmit="return confirm('Kết thúc tour?');">
                                        <input type="hidden" name="departure_id" value="<?= $assign['departure_id'] ?>">
                                        <button class="btn btn-success fw-bold px-4 py-2 pulse-anim">
                                            <i class="bi bi-check-circle-fill me-2"></i> HOÀN TẤT CHUYẾN ĐI
                                        </button>
                                    </form>
                                <?php else: ?>
                                    <p class="text-muted small">Hoàn thành các bước trên để mở khóa.</p>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4 sticky-top" style="top: 20px; z-index: 1;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-secondary"><i class="bi bi-journal-text me-2"></i>Nhật ký Tour</h6>
                </div>
                <div class="card-body">
                    <form action="<?= BASE_URL ?>?act=hdv-log-store" method="POST" enctype="multipart/form-data" class="mb-3">
                        <input type="hidden" name="assign_id" value="<?= $assign['departure_id'] ?>">
                        <input type="hidden" name="log_date" value="<?= date('Y-m-d') ?>">
                        <textarea name="description" class="form-control bg-light mb-2" rows="2" placeholder="Ghi chú nhanh..." required></textarea>
                        <button class="btn btn-sm btn-primary w-100"><i class="bi bi-send"></i> Gửi</button>
                    </form>
                    <div class="list-group list-group-flush small" style="max-height: 400px; overflow-y: auto;">
                        <?php if (!empty($logs)) foreach ($logs as $log): ?>
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between text-muted" style="font-size:0.75rem">
                                    <span><?= date('H:i d/m', strtotime($log['created_at'])) ?></span>
                                </div>
                                <div><?= nl2br(htmlspecialchars($log['description'])) ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .stepper {
        position: relative;
        padding-left: 20px;
    }

    .stepper .step {
        position: relative;
        padding-left: 45px;
        padding-bottom: 30px;
    }

    .stepper .step::before {
        content: '';
        position: absolute;
        left: 15px;
        top: 35px;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
        z-index: 0;
    }

    .stepper .step:last-child::before {
        display: none;
    }

    .step-icon {
        position: absolute;
        left: 0;
        top: 0;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: #e9ecef;
        color: #6c757d;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        z-index: 1;
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px #e9ecef;
    }

    .step.active .step-icon {
        background: #0d6efd;
        color: #fff;
        box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.2);
    }

    .step.completed .step-icon {
        background: #198754;
        color: #fff;
        box-shadow: 0 0 0 2px #198754;
    }

    .step.completed::before {
        background: #198754;
    }

    .step.disabled {
        opacity: 0.5;
        pointer-events: none;
    }

    @keyframes pulse-green {
        0% {
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.7);
        }

        70% {
            box-shadow: 0 0 0 10px rgba(25, 135, 84, 0);
        }

        100% {
            box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
        }
    }

    .pulse-anim {
        animation: pulse-green 2s infinite;
    }
</style>