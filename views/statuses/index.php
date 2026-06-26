<?php

$stmt = db()->query('SELECT * FROM task_statuses ORDER BY sort_order');
$statuses = $stmt->fetchAll();
?>

<div class="toolbar">
    <a href="index.php?action=status_create" class="btn btn-primary">+ Создать статус</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Порядок</th>
            <th>Действия</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($statuses as $s): ?>
        <tr>
            <td><?= $s['id'] ?></td>
            <td><?= escape($s['name']) ?></td>
            <td><?= $s['sort_order'] ?></td>
            <td class="actions">
                <a href="index.php?action=status_edit&id=<?= $s['id'] ?>" class="btn-sm">✏️</a>
                <a href="index.php?action=status_delete&id=<?= $s['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить статус?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
