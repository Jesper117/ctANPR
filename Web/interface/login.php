<?php
require_once("../services/UserService.php");
require_once("../services/SafetyService.php");

if (session_status() == PHP_SESSION_NONE)
{
    session_start();
}

if (isset($_POST["username"]) && isset($_POST["password"]))
{
    $SafetyService = new SafetyService();
    $SafetyService->StringCheck($_POST["username"]);
    $SafetyService->StringCheck($_POST["password"]);

    $UserService = new UserService();
    $UserService->Login($_POST["username"], $_POST["password"]);

    header("Location: ../src/login.php");

    exit();
}
else
{
    $_SESSION["login_callback"] = [
        "success" => "false",
        "message" => "Incorrect credentials."
    ];
    header("Location: ../src/login.php");

    exit();
}
?>