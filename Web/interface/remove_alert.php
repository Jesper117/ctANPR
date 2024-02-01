<?php
require_once("../services/AlertService.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["id"])) {
    $AlertService = new AlertService();

    $UserId = $_SESSION["user"];
    $Result = $AlertService->RemoveAlert($UserId, $_GET["id"]);

    if ($Result == 1) {
        $_SESSION["callback"] = array("type" => "success", "message" => "Alert succesvol verwijderd.");
        header("Location: ../src/alerts.php");
        exit();
    } else {
        $_SESSION["callback"] = array("type" => "error", "message" => "Er is iets fout gegaan.");
        header("Location: ../src/alerts.php");
        exit();
    }
}
?>