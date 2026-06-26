<p class="report-description">Распределение задач по категориям.</p>

<?php
$stmt = db()->query('SELECT c.name AS category, COUNT(t.id) AS task_count FROM categories c LEFT JOIN tasks t ON t.category_id = c.id GROUP BY c.id, c.name ORDER BY c.name');
$rows = $stmt->fetchAll();
?>

<table class="table">
    <thead>
        <tr>
            <th>Категория</th>
            <th>Количество задач</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($rows as $row): ?>
        <tr>
            <td><?= escape($row['category']) ?></td>
            <td><?= $row['task_count'] ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>
