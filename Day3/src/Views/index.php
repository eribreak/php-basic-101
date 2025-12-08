<?php
$layoutFile = __DIR__ . '/layout.php';
ob_start();
?>

<h1>TodoList</h1>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success"><?= htmlspecialchars($_SESSION['success']) ?></div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error"><?= htmlspecialchars($_SESSION['error']) ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<p><a href="/create" class="btn btn-primary">Thêm Todo Mới</a></p>

<div class="filter">
    <a href="/" class="<?= $currentStatus === null ? 'active' : '' ?>">Tất cả</a>
    <a href="/?status=pending" class="<?= $currentStatus === 'pending' ? 'active' : '' ?>">Đang làm</a>
    <a href="/?status=completed" class="<?= $currentStatus === 'completed' ? 'active' : '' ?>">Hoàn thành</a>
</div>

<?php if (empty($todos)): ?>
    <p>Chưa có todo nào.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($todos as $todo): ?>
                <tr>
                    <td><?= htmlspecialchars((string) $todo['id']) ?></td>
                    <td><strong><?= htmlspecialchars($todo['title']) ?></strong></td>
                    <td><?= htmlspecialchars($todo['description'] ?? '') ?></td>
                    <td><?= htmlspecialchars($todo['category_name'] ?? 'Không có') ?></td>
                    <td><?= $todo['status'] === 'completed' ? 'Hoàn thành' : 'Đang làm' ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($todo['created_at'])) ?></td>
                    <td>
                        <a href="/toggle-status?id=<?= $todo['id'] ?>" class="btn btn-success btn-sm">Đổi</a>
                        <a href="/edit?id=<?= $todo['id'] ?>" class="btn btn-primary btn-sm">Sửa</a>
                        <a href="/delete?id=<?= $todo['id'] ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Bạn chắc chắn muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php
$content = ob_get_clean();
require $layoutFile;
?>

