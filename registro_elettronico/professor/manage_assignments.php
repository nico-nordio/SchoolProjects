<?php
session_start();
require '../db.php';

if ($_SESSION["role"] !== "professor") {
    die("Accesso negato.");
}

$prof_id = $_SESSION["user_id"];
$subject = $_SESSION["subject"];

// Recupera tutti i compiti creati da lui
$stmt = $conn->prepare("SELECT * FROM assignments WHERE professor_id = ? ORDER BY due_date DESC");
$stmt->bind_param("i", $prof_id);
$stmt->execute();
$assignments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Gestione Compiti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('../assets/profs.png') no-repeat center center fixed;
            background-size: cover;
            /* Adatta l'immagine allo schermo */
            color: white;
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

        .card-header {
            background-color:rgb(245, 40, 33);
        }

        th,td{
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header text-white d-flex justify-content-between">
                <h4 class="mb-0">I Miei Compiti – <?= ucfirst($subject) ?></h4>
                <a href="add_assignment.php" class="btn btn-success btn-sm">+ Aggiungi Nuovo</a>
            </div>
            <div class="card-body">
                <?php if (isset($_GET['eliminato'])): ?>
                    <div class="alert alert-success">Compito eliminato con successo.</div>
                <?php endif; ?>
                <?php if (isset($_GET['modifica'])): ?>
                    <div class="alert alert-success">Compito modificato con successo.</div>
                <?php endif; ?>

                <?php if ($assignments->num_rows > 0): ?>
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Titolo</th>
                                <th>Descrizione</th>
                                <th>Scadenza</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $assignments->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['title']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?></td>
                                    <td><?= $row['due_date'] ?></td>
                                    <td>
                                        <a href="edit_assignment.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">✏️ Modifica</a>
                                        <a href="delete_assignment.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Confermi l\'eliminazione?')">🗑️ Elimina</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="alert alert-info">Nessun compito presente.</div>
                <?php endif; ?>

                <a href="insert_grade.php" class="btn btn-outline-info">Inserisci Voti</a>
                <a href="view_students.php" class="btn btn-outline-secondary">Visualizza Medie Studenti</a>
                <a href="../logout.php" class="btn btn-danger mt-3 float-end">Logout</a>
            </div>
        </div>
    </div>

</body>

</html>