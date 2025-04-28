<?php
require __DIR__ . '/../../../vendor/autoload.php';

use App\Core\Database;

header('Content-Type: application/json');

$db = Database::getConnection();

$stmt = $db->query('SELECT titolo AS title, data_evento AS start FROM proposte');
$eventi = $stmt->fetchAll();

echo json_encode($eventi);
