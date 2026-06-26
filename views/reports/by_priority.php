<p class="report-description">Распределение задач по приоритетам.</p>

<?php
$stmt = db()->query('SELECT p.name AS priority, COUNT(t.id) AS task_count FROM priorities p LEFT JOIN tasks t ON t.priority_id = p.id GROUP BY p.id, p.name ORDER BY p.sort_order');
$rows = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>Приоритет</th>
            <th>Количество задач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($row['priority']) ?></td>
            <td><?= $row['task_count'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
