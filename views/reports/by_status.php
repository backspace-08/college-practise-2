<p class="report-description">Количество задач по каждому статусу.</p>

<?php
$stmt = db()->query('SELECT ts.name AS status, COUNT(t.id) AS task_count FROM task_statuses ts LEFT JOIN tasks t ON t.status_id = ts.id GROUP BY ts.id, ts.name ORDER BY ts.sort_order');
$rows = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>Статус</th>
            <th>Количество задач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($row['status']) ?></td>
            <td><?= $row['task_count'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
