<?php

$stmt = db()->query('SELECT p.*, COUNT(t.id) AS task_count FROM projects p LEFT JOIN tasks t ON t.project_id = p.id GROUP BY p.id ORDER BY p.created_at DESC');
$projects = $stmt->fetchAll();
?>

<?php if (isAdmin()): ?>
<div class="toolbar">
    <a href="index.php?action=project_create" class="btn btn-primary">+ Создать проект</a>
</div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Описание</th>
            <th>Задач</th>
            <th>Создан</th>
            <?php if (isAdmin()): ?>
            <th>Действия</th>
            <?php endif; ?>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($projects as $project): ?>
        <tr>
            <td><?= $project['id'] ?></td>
            <td><?= escape($project['name']) ?></td>
            <td><?= escape(mb_substr($project['description'], 0, 100)) ?></td>
            <td><?= $project['task_count'] ?></td>
            <td><?= escape($project['created_at']) ?></td>
            <?php if (isAdmin()): ?>
            <td class="actions">
                <a href="index.php?action=project_edit&id=<?= $project['id'] ?>" class="btn-sm">✏️</a>
                <a href="index.php?action=project_delete&id=<?= $project['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Удалить проект? Все задачи тоже удалятся.')">🗑️</a>
            </td>
            <?php endif; ?>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
