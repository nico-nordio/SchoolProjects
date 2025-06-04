<?php
session_start();
require '../db.php';

if ($_SESSION["role"] !== "professor") die("Accesso negato.");

$prof_id = $_SESSION["user_id"];
$assignment_id = $_GET["id"] ?? null;

if (!$assignment_id) die("Compito non specificato.");

// Verifica ownership
$stmt = $conn->prepare("SELECT * FROM assignments WHERE id = ? AND professor_id = ?");
$stmt->bind_param("ii", $assignment_id, $prof_id);
$stmt->execute();
$result = $stmt->get_result();
$assignment = $result->fetch_assoc();

if (!$assignment) die("Accesso non autorizzato: compito inesistente o non tuo.");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = $_POST["title"];
    $description = $_POST["description"];
    $due_date = $_POST["due_date"];

    $update = $conn->prepare("UPDATE assignments SET title = ?, description = ?, due_date = ? WHERE id = ?");
    $update->bind_param("sssi", $title, $description, $due_date, $assignment_id);
    $update->execute();

    header("Location: manage_assignments.php?modifica=ok");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Modifica Compito</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/profs.png') no-repeat center center fixed;
            background-size: cover;
            /* Adatta l'immagine allo schermo */
            color: white;
        }

        .card-header {
            text-align: center;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9);
            /* Sfondo semi-trasparente chiaro */
            color: #343a40;
            /* Testo scuro per contrasto */
            border-radius: 15px;
            /* Angoli arrotondati */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            /* Ombra */
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5 col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-warning text-white">
                <h4>Modifica Compito</h4>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Titolo</label>
                        <input type="text" name="title" class="form-control" value="<?= htmlspecialchars($assignment['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea name="description" class="form-control" required><?= htmlspecialchars($assignment['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scadenza</label>
                        <input type="date" name="due_date" class="form-control" value="<?= $assignment['due_date'] ?>" required>
                    </div>
                    <button type="submit" class="btn btn-success">Salva Modifiche</button>
                    <a href="manage_assignments.php" class="btn btn-secondary">Annulla</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>