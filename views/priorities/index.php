<?php

$stmt = db()->query('SELECT * FROM priorities ORDER BY sort_order');
$priorities = $stmt->fetchAll();
?>

<div class="toolbar">
    <a href="index.php?action=priority_create" class="btn btn-primary">+ Создать приоритет</a>
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
        <?php foreach ($priorities as $pr): ?>
        <tr>
            <td><?= $pr['id'] ?></td>
            <td><?= escape($pr['name']) ?></td>
            <td><?= $pr['sort_order'] ?></td>
            <td class="actions">
                <a href="index.php?action=priority_edit&id=<?= $pr['id'] ?>" class="btn-sm">✏️</a>
                <a href="index.php?action=priority_delete&id=<?= $pr['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить приоритет?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
