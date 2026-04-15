<?php $pageTitle = 'Quản lý người dùng'; require_once __DIR__ . '/../layouts/admin-header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title"><i class="fas fa-users mr-2"></i>Danh sách người dùng</h3>
        <span class="badge badge-info">Tổng: <?= $total ?></span>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="thead-light">
                <tr>
                    <th>#</th><th>Tên</th><th>Email</th><th>SĐT</th><th>Vai trò</th><th>Trạng thái</th><th>Ngày tạo</th><th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($users)): ?>
            <tr><td colspan="8" class="text-center text-muted py-4">Chưa có người dùng</td></tr>
            <?php else: ?>
            <?php foreach ($users as $i => $u): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td class="font-weight-bold"><?= sanitize($u['name']) ?></td>
                <td><?= sanitize($u['email']) ?></td>
                <td><?= sanitize($u['phone'] ?? '—') ?></td>
                <td>
                    <?php if ($u['role']==='admin'): ?>
                        <span class="badge badge-danger">Admin</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">User</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($u['is_active']): ?>
                        <span class="badge badge-success">Hoạt động</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Đã khóa</span>
                    <?php endif; ?>
                </td>
                <td><small><?= date('d/m/Y', strtotime($u['created_at'])) ?></small></td>
                <td>
                    <?php if ($u['role'] !== 'admin'): ?>
                    <a href="<?= APP_URL ?>/index.php?act=toggle-user&id=<?= $u['id'] ?>"
                       class="btn btn-xs <?= $u['is_active']?'btn-outline-warning':'btn-outline-success' ?>"
                       title="<?= $u['is_active']?'Khóa':'Mở khóa' ?>">
                        <i class="fas fa-<?= $u['is_active']?'ban':'unlock' ?>"></i>
                    </a>
                    <a href="<?= APP_URL ?>/index.php?act=delete-user-admin&id=<?= $u['id'] ?>"
                       class="btn btn-xs btn-outline-danger" title="Xóa"
                       onclick="return confirm('Xóa người dùng này?')">
                        <i class="fas fa-trash"></i>
                    </a>
                    <?php else: ?>
                    <span class="text-muted small">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($total > ITEMS_PER_PAGE): ?>
    <div class="card-footer d-flex justify-content-center">
        <?= paginationLinks($total, ITEMS_PER_PAGE, $page, APP_URL . '/index.php?act=admin-users') ?>
    </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../layouts/admin-footer.php'; ?>
