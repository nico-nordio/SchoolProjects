<?php
session_start();
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION["role"] == "student") {
    header("Location: student/grades.php");
} elseif ($_SESSION["role"] == "professor") {
    header("Location: professor/manage_assignments.php");
}
