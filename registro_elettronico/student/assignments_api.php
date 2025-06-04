<?php
session_start();
require_once '../db.php';

// Verifica autenticazione studente
$student_id = $_SESSION['user_id'] ?? null;
if (!$student_id || $_SESSION['role'] !== 'student') {
    http_response_code(401);
    echo json_encode(["error" => "Non autorizzato"]);
    exit;
}

// Query compiti
$sql = "SELECT title, description, subject, due_date FROM assignments ORDER BY due_date ASC";
$result = $conn->query($sql);

$events = [];

while ($row = $result->fetch_assoc()) {
    $events[] = [
        'title' => $row['subject'] . ": " . $row['title'],
        'start' => $row['due_date'],
        'subject' => $row['subject'],
        'description' => $row['description'],
        'due_date' => $row['due_date'],
    ];
}

// Imposta header e ritorna JSON
header('Content-Type: application/json');
echo json_encode($events);
