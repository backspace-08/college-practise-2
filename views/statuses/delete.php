<?php

$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = db()->prepare('DELETE FROM task_statuses WHERE id = :id');
    $stmt->execute(['id' => $id]);
    flash('success', 'Статус удалён');
} catch (PDOException $e) {
    flash('error', 'Нельзя удалить статус, к которому привязаны задачи');
}

redirect('index.php?action=statuses');
