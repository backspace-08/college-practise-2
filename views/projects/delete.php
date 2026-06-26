<?php

$id = (int) ($_GET['id'] ?? 0);

$stmt = db()->prepare('DELETE FROM projects WHERE id = :id');
$stmt->execute(['id' => $id]);

flash('success', 'Проект удалён');
redirect('index.php?action=projects');
