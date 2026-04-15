<?php $pageTitle = 'Dashboard'; require_once __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Stats Row -->
<div class="row">
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3><?= $stats['total_users'] ?></h3>
                <p>Người dùng</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="<?= APP_URL ?>/index.php?act=admin-users" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3><?= $stats['car_by_status']['approved'] ?? 0 ?></h3>
                <p>Xe đã duyệt</p>
            </div>
            <div class="icon"><i class="fas fa-car"></i></div>
            <a href="<?= APP_URL ?>/index.php?act=admin-cars&status=approved" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3><?= $stats['car_by_status']['pending'] ?? 0 ?></h3>
                <p>Chờ duyệt</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="<?= APP_URL ?>/index.php?act=admin-cars&status=pending" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3><?= $stats['unread_messages'] ?></h3>
                <p>Tin nhắn mới</p>
            </div>
            <div class="icon"><i class="fas fa-envelope"></i></div>
            <a href="<?= APP_URL ?>/index.php?act=admin-messages" class="small-box-footer">
                Chi tiết <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- Status Summary -->
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-chart-pie mr-2"></i>Thống kê xe theo trạng thái</h3>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <?php
                    $statusMap = ['approved'=>['Đã duyệt','success'], 'pending'=>['Chờ duyệt','warning'], 'rejected'=>['Từ chối','danger'], 'sold'=>['Đã bán','secondary']];
                    foreach ($statusMap as $key => [$label, $color]):
                        $cnt = $stats['car_by_status'][$key] ?? 0;
                    ?>
                    <tr>
                        <td><span class="badge badge-<?= $color ?>"><?= $label ?></span></td>
                        <td>
                            <div class="progress" style="height:16px;">
                                <div class="progress-bar bg-<?= $color ?>" style="width:<?= $cnt>0?min(100, ($cnt/array_sum($stats['car_by_status']))*100):0 ?>%">
                                </div>
                            </div>
                        </td>
                        <td class="text-right font-weight-bold"><?= $cnt ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-bolt mr-2"></i>Thao tác nhanh</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6 mb-2">
                        <a href="<?= APP_URL ?>/index.php?act=admin-cars&status=pending"
                           class="btn btn-warning btn-block">
                            <i class="fas fa-clock mr-1"></i>Duyệt xe chờ
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="<?= APP_URL ?>/index.php?act=admin-users" class="btn btn-info btn-block">
                            <i class="fas fa-users mr-1"></i>Quản lý user
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="<?= APP_URL ?>/index.php?act=admin-brands" class="btn btn-secondary btn-block">
                            <i class="fas fa-tags mr-1"></i>Hãng xe
                        </a>
                    </div>
                    <div class="col-6 mb-2">
                        <a href="<?= APP_URL ?>/index.php?act=admin-messages" class="btn btn-danger btn-block">
                            <i class="fas fa-envelope mr-1"></i>Tin nhắn
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
