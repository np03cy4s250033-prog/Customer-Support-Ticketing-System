<?php
if (!function_exists('e')) {
    function e($string) {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
}

function generate_ticket_code(): string {
    return 'T-' . strtoupper(bin2hex(random_bytes(3)));
}

function get_ticket(PDO $pdo, int $id) {
    $stmt = $pdo->prepare("SELECT * FROM tickets WHERE id = :id");
    $stmt->execute([':id' => $id]);
    return $stmt->fetch();
}

function search_tickets(PDO $pdo, array $filters = []) {
    $sql = "SELECT * FROM tickets WHERE 1 ";
    $params = [];

    if (!empty($filters['issue_type'])) {
        $sql .= "AND issue_type = :issue_type ";
        $params[':issue_type'] = $filters['issue_type'];
    }
    if (!empty($filters['priority'])) {
        $sql .= "AND priority = :priority ";
        $params[':priority'] = $filters['priority'];
    }
    if (!empty($filters['date_from'])) {
        $sql .= "AND DATE(created_at) >= :from ";
        $params[':from'] = $filters['date_from'];
    }
    if (!empty($filters['date_to'])) {
        $sql .= "AND DATE(created_at) <= :to ";
        $params[':to'] = $filters['date_to'];
    }
    if (!empty($filters['q'])) {
        $sql .= "AND (ticket_code LIKE :q OR customer_email LIKE :q OR customer_name LIKE :q) ";
        $params[':q'] = '%' . $filters['q'] . '%';
    }

    $sql .= "ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
