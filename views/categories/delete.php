<?php

$id = (int) ($_GET['id'] ?? 0);
$stmt = db()->prepare('DELETE FROM categories WHERE id = :id');
$stmt->execute(['id' => $id]);
flash('success', 'Категория удалена');
redirect('index.php?action=categories');
