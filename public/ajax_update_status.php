<?php
require_once '../config/db.php';

header('Content-Type: application/json');

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$status = $_POST['status'] ?? '';

$allowed = ['open','in_progress','resolved','closed'];

if ($id <= 0 || !in_array($status, $allowed, true)) {
    echo json_encode(['ok' => false]);
    exit;
}

$stmt = $pdo->prepare("UPDATE tickets SET status = :status WHERE id = :id");
$stmt->execute([':status' => $status, ':id' => $id]);

function badgeClass($status) {
    if ($status === 'open') return 'badge-warning';
    if ($status === 'in_progress') return 'badge-warning';
    if ($status === 'resolved') return 'badge-success';
    if ($status === 'closed') return 'badge-danger';
    return 'badge-warning';
}

echo json_encode([
    'ok' => true,
    'status' => $status,
    'badgeClass' => badgeClass($status)
]);
