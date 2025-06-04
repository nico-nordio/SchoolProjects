<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <title>📅 Calendario Compiti</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #1e272e;
            /* Sfondo scuro */
            padding-top: 20px;
        }

        h2 {
            color: white;
        }

        #calendar {
            --fc-small-font-size: .80em; 
            max-width: 900px;
            margin: 0 auto;
            background-color: whitesmoke;
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        #calendar h2 {
            color: #333;
        }

        a {
            text-decoration: none;
            color: #007bff;
        }

        .fc-daygrid-event {
            cursor: pointer;
        }

        .fc-daygrid-event:hover {
            background-color: #007bff;
            color: white;
        }

        .fc-day-today {
            border-radius: 1zSAAA5px;
        }

        .modal-header {
            background-color: #3788d8;
            color: white;          
        }

        .modal-body {
            background-color: #f8f9fa;
            border-radius: 15px;       
        }

        .fc-daygrid-day-number {
            color: inherit;
            text-decoration: none;
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
            z-index: 10;
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

        .logout-btn {
            position: fixed;
            right: 10px;
            /* Posiziona il pulsante a destra */
            bottom: 10px;
            /* Posiziona il pulsante in basso */
            z-index: 11;
        }

        .nav-btn:hover {
            cursor: pointer;
            color: white;
            font-weight: bold;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2 class="text-center mb-4">📅 Compiti Assegnati</h2>
        <div id="calendar"></div>
    </div>

    <!-- MODALE DETTAGLI COMPITO -->
    <div class="modal fade" id="assignmentModal" tabindex="-1" aria-labelledby="assignmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content bg-light">
                <div class="modal-header">
                    <h5 class="modal-title" id="assignmentModalLabel">📘 Dettaglio Compito</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Materia:</strong> <span id="modalSubject"></span></p>
                    <p><strong>Titolo:</strong> <span id="modalTitle"></span></p>
                    <p><strong>Descrizione:</strong> <span id="modalDescription"></span></p>
                    <p><strong>Scadenza:</strong> <span id="modalDueDate"></span></p>
                </div>
            </div>
        </div>
    </div>

    <!-- JS LIBRARIES -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'it',
                firstDay: 1,
                events: '/registro_elettronico/student/assignments_api.php',
                eventClick: function(info) {
                    const event = info.event;
                    const ext = event.extendedProps;

                    // Pulizia testo da caratteri sporchi tipo \r\n
                    function clean(text) {
                        return (text || '').replace(/[\r\n]+/g, '').trim();
                    }

                    document.getElementById('modalTitle').textContent = clean(event.title.split(":")[1]);
                    document.getElementById('modalSubject').textContent = clean(ext.subject);
                    document.getElementById('modalDescription').textContent = clean(ext.description);
                    document.getElementById('modalDueDate').textContent = clean(ext.due_date);

                    const modal = new bootstrap.Modal(document.getElementById('assignmentModal'));
                    modal.show();
                }
            });

            calendar.render();
        });
    </script>

    <!-- Barra inferiore -->
    <div class="bottom-bar">
        <div onclick="location.href='calendar.php'" class="active">📅<br><small>Calendario</small></div>
        <div class="nav-btn">📕<br><small>Registro</small></div>
        <div onclick="location.href='grades.php'" class="nav-btn">🏅<br><small>Valutazioni</small></div>
    </div>
    <a href="../logout.php" class="btn btn-danger mt-2 float-end logout-btn">Logout</a>
</body>

</html>