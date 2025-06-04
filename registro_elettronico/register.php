<?php
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confermapassword"];

    if (empty($password) || empty($confirmPassword)) {
        echo "<div class='alert alert-danger'>Entrambi i campi password sono obbligatori.</div>";
    } elseif ($password !== $confirmPassword) {
        echo "<div class='alert alert-danger'>Le password non corrispondono.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<div class='alert alert-danger'>Email non valida.</div>";
    } else {
        $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($res->num_rows > 0) {
            echo "<div class='alert alert-danger'>Email già registrata.</div>";
        } else {
            $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
            $role = $_POST["role"];
            $subject = $role === "professor" ? $_POST["subject"] : NULL;

            $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, subject) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $name, $email, $hashedPassword, $role, $subject);
            $stmt->execute();
            echo "<div class='alert alert-success'>Registrazione completata. <a href='login.php'>Accedi</a></div>";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Registrazione</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        function toggleSubject() {
            const role = document.getElementById("roleSelect").value;
            document.getElementById("subjectDiv").style.display = role === "professor" ? "block" : "none";
        }
    </script>
    <style>
        body {
            background: url('assets/wallpaper.png') no-repeat center center fixed;
            background-size: cover;
            /* Adatta l'immagine allo schermo */
            color: white;
        }
        .card-header{
            text-align: center;
        }
    </style>
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h3>Registrazione</h3>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Nome e Cognome</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Conferma Password</label>
                        <input type="password" name="confermapassword" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ruolo</label>
                        <select name="role" id="roleSelect" class="form-select" onchange="toggleSubject()">
                            <option value="student">Studente</option>
                            <option value="professor">Professore</option>
                        </select>
                    </div>
                    <div class="mb-3" id="subjectDiv" style="display: none;">
                        <label class="form-label">Materia</label>
                        <select name="subject" class="form-select">
                            <option value="italiano">Italiano</option>
                            <option value="storia">Storia</option>
                            <option value="informatica">Informatica</option>
                            <option value="scienze motorie">Scienze Motorie</option>
                            <option value="tpsit">TPSIT</option>
                            <option value="gpoi">GPOI</option>
                            <option value="sistemi e reti">Sistemi e Reti</option>
                            <option value="religione">Religione</option>
                            <option value="matematica">Matematica</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success">Registrati</button>
                    <a href="login.php" class="btn btn-primary">Accedi</a>
                </form>
            </div>
        </div>
    </div>
</body>

</html>