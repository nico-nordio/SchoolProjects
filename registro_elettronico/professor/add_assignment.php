<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'professor') {
    header("Location: ../login.php");
    exit;
}

$professor_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $due_date = $_POST['due_date'];

    // Ottieni la materia del professore
    $stmt = $conn->prepare("SELECT subject FROM users WHERE id = ?");
    $stmt->bind_param("i", $professor_id);
    $stmt->execute();
    $stmt->bind_result($subject);
    $stmt->fetch();
    $stmt->close();

    if (!$subject) {
        die("Errore: Nessuna materia assegnata al professore.");
    }

    // Inserisci compito con materia automatica
    $stmt = $conn->prepare("INSERT INTO assignments (title, description, subject, due_date, professor_id) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssi", $title, $description, $subject, $due_date, $professor_id);

    if ($stmt->execute()) {
        header("Location: manage_assignments.php");
    } else {
        echo "Errore nell'inserimento: " . $stmt->error;
    }

    $stmt->close();
}
?>


<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Aggiungi Compito</title>
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
    <div class="container mt-5">
        <div class="card shadow-sm col-md-8 mx-auto">
            <div class="card-header bg-primary text-white">
                <h4>Aggiungi Compito</h4>
            </div>
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Titolo</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Descrizione</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Scadenza</label>
                        <input type="date" name="due_date" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Aggiungi</button>
                </form>

                <hr>
                <a href="manage_assignments.php" class="btn btn-outline-primary mt-3">← Torna</a>
                <a href="../logout.php" class="btn btn-danger float-end">Logout</a>
            </div>
        </div>
    </div>
</body>

</html>