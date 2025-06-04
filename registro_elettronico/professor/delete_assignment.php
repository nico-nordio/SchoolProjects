<?php
session_start();
require '../db.php';

if ($_SESSION["role"] !== "professor") die("Accesso negato.");

$prof_id = $_SESSION["user_id"];
$assignment_id = $_GET["id"] ?? null;

if (!$assignment_id) die("ID compito non fornito.");

// Verifica ownership
$stmt = $conn->prepare("SELECT * FROM assignments WHERE id = ? AND professor_id = ?");
$stmt->bind_param("ii", $assignment_id, $prof_id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

if (!$assignment) die("Accesso non autorizzato: compito non trovato o non tuo.");

// Elimina
$delete = $conn->prepare("DELETE FROM assignments WHERE id = ?");
$delete->bind_param("i", $assignment_id);
$delete->execute();

header("Location: manage_assignments.php?eliminato=ok");
exit;
