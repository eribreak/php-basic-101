<h1><?= $viewData['title'] ?? '' ?></h1>

<a href="/users/create" class="btn btn-success">Tạo User Mới</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Tên</th>
            <th>Email</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach (($viewData['users'] ?? []) as $user): ?>
            <tr>
                <td><?= $user['id'] ?></td>
                <td><?= $user['name'] ?></td>
                <td><?= $user['email'] ?></td>
                <td>
                    <a href="/users/show?id=<?= $user['id'] ?>" class="btn">Xem Chi Tiết</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

