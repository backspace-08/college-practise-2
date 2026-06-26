<?php

$stmt = db()->query('SELECT c.*, COUNT(t.id) AS task_count FROM categories c LEFT JOIN tasks t ON t.category_id = c.id GROUP BY c.id ORDER BY c.name');
$categories = $stmt->fetchAll();
?>

<?php if (isAdmin()): ?>
<div class="toolbar">
    <a href="index.php?action=category_create" class="btn btn-primary">+ Создать категорию</a>
</div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Задач</th>
            <?php if (isAdmin()): ?>
            <th>Действия</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($categories as $cat): ?>
        <tr>
            <td><?= $cat['id'] ?></td>
            <td><?= escape($cat['name']) ?></td>
            <td><?= escape($cat['description']) ?></td>
            <td><?= $cat['task_count'] ?></td>
            <?php if (isAdmin()): ?>
            <td class="actions">
                <a href="index.php?action=category_edit&id=<?= $cat['id'] ?>" class="btn-sm">✏️</a>
                <a href="index.php?action=category_delete&id=<?= $cat['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить категорию?')">🗑️</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
