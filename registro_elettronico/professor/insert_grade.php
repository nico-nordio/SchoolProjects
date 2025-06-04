<?php
session_start();
require '../db.php';

if ($_SESSION["role"] !== "professor") die("Accesso negato.");

// Recupera l'ID del professore e la materia dalla sessione
$professor_id = $_SESSION["user_id"];
$subject = $_SESSION["subject"];

// Recupera gli studenti
$students = $conn->query("SELECT id, name FROM users WHERE role='student'");

// Recupera i compiti creati dal professore loggato e relativi alla sua materia
$assignments = $conn->prepare("SELECT id, title FROM assignments WHERE professor_id = ? AND subject = ?");
$assignments->bind_param("is", $professor_id, $subject);
$assignments->execute();
$assignments_result = $assignments->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $student_id = $_POST["student_id"];
    $assignment_id = $_POST["assignment_id"];
    $grade = $_POST["grade"];

    $stmt = $conn->prepare("INSERT INTO grades (student_id, assignment_id, professor_id, grade_value, subject) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiids", $student_id, $assignment_id, $professor_id, $grade, $subject);
    $stmt->execute();

    $message = "Voto inserito con successo.";
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Inserisci Voti</title>
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
            <div class="card-header bg-warning">
                <h4>Inserisci Voto - <?= ucfirst($subject) ?></h4>
            </div>
            <div class="card-body">
                <?php if (isset($message)): ?>
                    <div class="alert alert-success"><?= $message ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Studente</label>
                        <select name="student_id" class="form-select">
                            <?php while ($row = $students->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['name'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Compito</label>
                        <select name="assignment_id" class="form-select">
                            <?php while ($row = $assignments_result->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>"><?= $row['title'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Voto</label>
                        <input type="number" name="grade" class="form-control" step="0.1" min="0" max="10" required>
                    </div>
                    <button type="submit" class="btn btn-success">Inserisci</button>
                </form>

                <hr>
                <a href="manage_assignments.php" class="btn btn-outline-primary">← Torna ai Compiti</a>
                <a href="view_students.php" class="btn btn-outline-info">Visualizza Voti</a>
                <a href="../logout.php" class="btn btn-danger float-end">Logout</a>
            </div>
        </div>
    </div>
</body>

</html>