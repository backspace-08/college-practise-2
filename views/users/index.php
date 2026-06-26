<?php

requireAdmin();

$stmt = db()->query('SELECT u.*, r.name AS role_name FROM users u JOIN roles r ON r.id = u.role_id ORDER BY u.username');
$users = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Имя пользователя</th>
            <th>Email</th>
            <th>Роль</th>
            <th>Создан</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $u): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= escape($u['username']) ?></td>
            <td><?= escape($u['email']) ?></td>
            <td><span class="badge badge-<?= $u['role_name'] ?>"><?= escape($u['role_name']) ?></span></td>
            <td><?= escape($u['created_at']) ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
