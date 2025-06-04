<?php
session_start();
require_once '../db.php';

// ID utente loggato
$student_id = $_SESSION['user_id'] ?? null;

if (!$student_id) {
    header("Location: ../login.php");
    exit;
}

// Recupero voti
$sql = "SELECT g.grade_value, g.subject, a.title AS assignment_name
        FROM grades g
        JOIN assignments a ON g.assignment_id = a.id
        WHERE g.student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

$materie = [];

while ($row = $result->fetch_assoc()) {
    $materia = $row['subject'];
    $voto = floatval($row['grade_value']);
    $compito = $row['assignment_name'];

    $materie[$materia][] = [
        'voto' => $voto,
        'compito' => $compito
    ];
}

// Media generale
$somma = [];
$conteggio = [];

foreach ($materie as $voti) {
    foreach ($voti as $voto) {
        $somma[] = $voto['voto'];
        $conteggio[] = 1;
    }
}

$media_totale = count($somma) > 0 ? round(array_sum($somma) / array_sum($conteggio), 2) : 0;
?>

<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>Registro Studente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background-color: #1e272e;
            /* Sfondo scuro */
            color: white;
            padding-top: 20px;
            font-family: 'Arial', sans-serif;
        }

        h2 {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            /* Ombra per il titolo */
        }

        /* Badge delle medie per materia (statici, senza effetto hover) */
        .badge-voto {
            width: 60px;
            /* Larghezza fissa */
            height: 60px;
            /* Altezza fissa */
            border-radius: 50%;
            /* Rende il cerchio perfetto */
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: white;
            flex-shrink: 0;
            /* Impedisce che il cerchio si ridimensioni */
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            /* Ombra statica */
            cursor: default;
            /* Disabilita il cursore interattivo */
            transition: none;
            /* Rimuove l'animazione */
        }

        /* Badge dei voti delle valutazioni (con effetto hover) */
        .badge-voto-valutazione {
            cursor: pointer;
            /* Indica che è interattivo */
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            /* Aggiunge l'animazione */
        }

        .badge-voto-valutazione:hover {
            transform: scale(1.1);
            /* Ingrandisce leggermente il cerchio */
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.5);
            /* Aggiunge un'ombra più grande */
        }

        /* Colori per i voti */
        .positivo {
            background-color: #4caf50;
            /* Verde per voti positivi */
        }

        .negativo {
            background-color: #e53935;
            /* Rosso per voti negativi */
        }

        .dashboard-box {
            background-color: #2c3e50;
            /* Sfondo scuro */
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            /* Ombra */
        }

        .circle {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #4caf50;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: bold;
            margin: auto;
            color: white;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            /* Ombra */
        }

        .bottom-bar {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: #2c3e50;
            display: flex;
            justify-content: center;
            padding: 10px 0;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.5);
            /* Ombra superiore */
        }

        .bottom-bar div {
            color: #ccc;
            text-align: center;
            margin: 0 35px;
            font-size: 16px;
            /* Aumenta la dimensione del font */
            font-weight: bold;
            /* Rende il testo più evidente */
        }

        .bottom-bar .active {
            color: #f44336;
            cursor: default;
            font-weight: bold;
            font-size: 18px;
            /* Aumenta leggermente la dimensione per l'elemento attivo */
        }

        .nav-btn:hover {
            cursor: pointer;
            color: white;
            font-weight: bold;
        }

        .modal-content {
            border-radius: 15px;
            /* Angoli arrotondati */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            /* Ombra */
            overflow: hidden;
            /* Evita che i contenuti escano dai bordi */
        }

        .modal-header {
            background-color: #3788d8;
            color: white;
            /* Testo bianco */
            border-bottom: none;
            /* Rimuove il bordo inferiore */
        }

        .modal-body {
            font-size: 16px;
            line-height: 1.5;
            background-color: #f8f9fa;
            /* Sfondo chiaro */
            color: #343a40;
            /* Testo scuro */
            border-radius: 10px;
            padding: 20px;
        }

        .modal-footer {
            background-color: #f8f9fa;
            /* Sfondo chiaro */
            border-top: none;
            /* Rimuove il bordo superiore */
        }

        .btn-close-white {
            filter: invert(1);
            /* Cambia il colore del pulsante di chiusura su sfondo scuro */
        }

        .text-primary {
            font-weight: bold;
            color: #007bff;
            /* Colore blu per evidenziare i dettagli */
        }

        .badge.bg-success {
            font-size: 18px;
            padding: 10px 15px;
            border-radius: 10px;
            /* Angoli arrotondati per il badge */
        }

        .logout-btn {
            position: fixed;
            right: 10px;
            /* Posiziona il pulsante a destra */
            bottom: 10px;
            /* Posiziona il pulsante in basso */
            z-index: 11;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center mb-4">🏅 Le Tue Valutazioni</h2>
    </div>

    <div class="container py-4">

        <!-- Box media generale -->
        <div class="row mb-4">
            <div class="col-md-3"> <!-- Media generale spostata a sinistra -->
                <div class="dashboard-box text-center">
                    <h5>Media Generale</h5>
                    <div class="circle"><?= $media_totale ?></div>
                </div>
            </div>

            <!-- Medie per ogni materia -->
            <div class="col-md-9"> <!-- Medie delle singole materie -->
                <div class="dashboard-box">
                    <h5>Medie per Materia</h5>
                    <div class="row">
                        <?php foreach ($materie as $materia => $voti): ?>
                            <?php
                            $media_materia = number_format(array_sum(array_column($voti, 'voto')) / count($voti), 1);
                            $badge_class = $media_materia >= 6 ? 'positivo' : 'negativo';
                            ?>
                            <div class="col-md-4 col-lg-4 mb-3"> <!-- 3 colonne per riga -->
                                <div class="d-flex align-items-center">
                                    <span class="badge-voto <?= $badge_class ?>"><?= $media_materia ?></span>
                                    <span class="ms-2"><?= strtoupper($materia) ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Box valutazioni -->
        <div class="dashboard-box">
            <h5>Valutazioni</h5>
            <div class="row">
                <?php foreach ($materie as $materia => $voti): ?>
                    <?php foreach ($voti as $dettaglio): ?>
                        <?php
                        $voto = $dettaglio['voto'];
                        $compito = $dettaglio['compito'];
                        $badge_class = $voto >= 6 ? 'positivo' : 'negativo';
                        ?>
                        <div class="col-md-3 col-lg-3 mb-3"> <!-- 4 colonne per riga -->
                            <div class="d-flex align-items-center">
                                <span class="badge-voto badge-voto-valutazione <?= $badge_class ?>"
                                    onclick="showAssignmentDetails('<?= htmlspecialchars($compito) ?>', <?= $voto ?>)">
                                    <?= $voto ?>
                                </span>
                                <span class="ms-2"><?= strtoupper($materia) ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </div>
        </div>

    </div>

    <!-- Barra inferiore -->
    <div class="bottom-bar">
        <div onclick="location.href='calendar.php'" class="nav-btn">📅<br><small>Calendario</small></div>
        <div class="nav-btn">📕<br><small>Registro</small></div>
        <div class="active">🏅<br><small>Valutazioni</small></div>

    </div>
    <a href="../logout.php" class="btn btn-danger mt-3 float-end logout-btn">Logout</a>

    <!-- Modal Dettagli Compito -->
    <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered"> <!-- Centra il modale -->
            <div class="modal-content"> <!-- Sfondo chiaro -->
                <div class="modal-header bg-primary text-white border-0"> <!-- Intestazione colorata -->
                    <h5 class="modal-title" id="assignmentModalLabel">📘 Dettagli del Compito</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    <div class="p-3 rounded bg-light"> <!-- Sfondo chiaro per i dettagli -->
                        <p><strong>📄 Compito:</strong> <span id="modalAssignmentName" class="text-primary"></span></p>
                        <p><strong>🏅 Voto:</strong> <span id="modalGrade" class="badge bg-success"></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showAssignmentDetails(compito, voto) {
            document.getElementById('modalAssignmentName').textContent = compito;
            document.getElementById('modalGrade').textContent = voto;

            const modal = new bootstrap.Modal(document.getElementById('assignmentModal'));
            modal.show();
        }
    </script>

</body>

</html>