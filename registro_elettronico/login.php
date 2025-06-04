<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($user = $res->fetch_assoc()) {
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["subject"] = $user["subject"];
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Password errata!";
        }
    } else {
        $error = "Utente non trovato!";
    }
}
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        body {
            background: url('assets/wallpaper.png') no-repeat center center fixed;
            background-size: cover; /* Adatta l'immagine allo schermo */
            color: white;
        }

        .card {
            background-color: rgba(255, 255, 255, 0.9); /* Sfondo semi-trasparente chiaro */
            color: #343a40; /* Testo scuro per contrasto */
            border-radius: 15px; /* Angoli arrotondati */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5); /* Ombra */
        }

        .card-header {
            background-color: #007bff; /* Sfondo blu per l'intestazione */
            color: white; /* Testo bianco */
            border-bottom: none;
            text-align: center;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0056b3; /* Colore più scuro al passaggio del mouse */
        }

        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
            transition: background-color 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #5a6268; /* Colore più scuro al passaggio del mouse */
        }

        h1 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Ombra per il titolo */
        }
    </style>
</head>

<body>
    <h1 class="text-center text-white mt-4">📘 Benvenuto nel Registro Elettronico</h1>
    <div class="container mt-5">
        <div class="card shadow-sm col-md-6 mx-auto">
            <div class="card-header">
                <h4>Accesso Utente</h4>
            </div>
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Inserisci la tua email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Inserisci la tua password" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Accedi</button>
                        <a href="register.php" class="btn btn-secondary">Registrati</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>