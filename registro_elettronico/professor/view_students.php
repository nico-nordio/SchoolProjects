<?php
session_start();
require '../db.php';

if ($_SESSION["role"] !== "professor") {
    die("Accesso negato.");
}

$subject = $_SESSION["subject"];

$stmt = $conn->prepare("
    SELECT u.name, u.email, COUNT(g.id) AS num_voti, AVG(g.grade_value) AS media
    FROM grades g
    JOIN users u ON g.student_id = u.id
    WHERE g.subject = ?
    GROUP BY g.student_id
");
$stmt->bind_param("s", $subject);
$stmt->execute();
$students = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Media Studenti - <?= ucfirst($subject) ?></title>
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

        th,td {
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">

    <div class="container mt-5">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0">Media Studenti – Materia: <?= ucfirst($subject) ?></h3>
            </div>
            <div class="card-body">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Numero Voti</th>
                            <th>Media</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $students->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= $row['num_voti'] ?></td>
                                <?php if ($row['media'] >= 6): ?>
                                    <td class="text-success"><strong><?= number_format($row['media'], 2) ?></strong></td>
                                <?php else: ?>
                                    <td class="text-danger"><strong><?= number_format($row['media'], 2) ?></strong></td>
                                <?php endif; ?>

                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <a href="insert_grade.php" class="btn btn-outline-primary mt-3">← Inserisci voti</a>
                <a href="manage_assignments.php" class="btn btn-outline-secondary mt-3">Torna ai Compiti</a>
                <a href="../logout.php" class="btn btn-danger mt-3 float-end">Logout</a>
            </div>
        </div>
    </div>

</body>

</html>