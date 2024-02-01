<?php
require_once("../services/AlertService.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST["name"], $_POST["plate"], $_POST["type"])) {
    $AlertService = new AlertService();
    $Result = $AlertService->CreateAlert($_POST["name"], $_POST["plate"], $_SESSION["user"], $_POST["type"]);

    if ($Result == 1) {
        $_SESSION["callback"] = array("type" => "success", "message" => "Alert succesvol aangemaakt.");
        header("Location: ../src/alerts.php");
        exit();
    } else {
        $_SESSION["callback"] = array("type" => "error", "message" => "Er is iets fout gegaan.");
        header("Location: ../src/alerts.php");
        exit();
    }
}
?>