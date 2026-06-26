<p class="report-description">Задачи, завершённые за последние 7 дней.</p>

<?php
$stmt = db()->query("SELECT t.title, p.name AS project, u.username, t.completed_at FROM tasks t JOIN projects p ON p.id = t.project_id JOIN users u ON u.id = t.user_id WHERE t.completed_at >= CURRENT_DATE - INTERVAL '7 days' ORDER BY t.completed_at DESC");
$rows = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>Задача</th>
            <th>Проект</th>
            <th>Исполнитель</th>
            <th>Завершена</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)): ?>
        <tr><td colspan="4">За последнюю неделю задач не завершено</td></tr>
        <?php else: ?>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($row['title']) ?></td>
            <td><?= escape($row['project']) ?></td>
            <td><?= escape($row['username']) ?></td>
            <td><?= escape($row['completed_at']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
