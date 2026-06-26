<?php

$id = (int) ($_GET['id'] ?? 0);

try {
    $stmt = db()->prepare('DELETE FROM priorities WHERE id = :id');
    $stmt->execute(['id' => $id]);
    flash('success', 'Приоритет удалён');
} catch (PDOException $e) {
    flash('error', 'Нельзя удалить приоритет, к которому привязаны задачи');
}

redirect('index.php?action=priorities');
