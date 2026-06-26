<p class="report-description">Задачи, у которых срок выполнения истёк, но они не завершены.</p>

<?php
$stmt = db()->query("SELECT t.title, p.name AS project, u.username, t.due_date, ts.name AS status FROM tasks t JOIN projects p ON p.id = t.project_id JOIN users u ON u.id = t.user_id JOIN task_statuses ts ON ts.id = t.status_id WHERE t.due_date < CURRENT_DATE AND t.completed_at IS NULL ORDER BY t.due_date");
$rows = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>Задача</th>
            <th>Проект</th>
            <th>Исполнитель</th>
            <th>Срок</th>
            <th>Статус</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($rows)): ?>
        <tr><td colspan="5">Просроченных задач нет</td></tr>
        <?php else: ?>
        <?php foreach ($rows as $row): ?>
        <tr class="row-overdue">
            <td><?= escape($row['title']) ?></td>
            <td><?= escape($row['project']) ?></td>
            <td><?= escape($row['username']) ?></td>
            <td><?= escape($row['due_date']) ?></td>
            <td><?= escape($row['status']) ?></td>
        </tr>
        <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>
