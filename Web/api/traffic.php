<?php
require_once("../services/NotificationService.php");

$NotificationService = new NotificationService();

$API_KEY = "55gYShdGkDvJMmfPvNBVtntVjckQ9v";

if (!isset($_GET["api_key"]) || $_GET["api_key"] !== $API_KEY) {
    http_response_code(401);
    echo json_encode(array("error" => "Unauthorized"));
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ctanpr";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function insertTraffic($conn, $location_id, $plate)
{
    $stmt = $conn->prepare("INSERT INTO traffic (kenteken, location_id) VALUES (?, ?)");
    $stmt->bind_param("si", $plate, $location_id);

    $start_time = microtime(true);

    $stmt->execute();

    $end_time = microtime(true);

    $time_taken = $end_time - $start_time;
    $time_taken = round($time_taken, 4);

    $data["fetch_time"] = $time_taken;

    return $data;
}

if (isset($_GET["kenteken"])) {
    $plate = $_GET["kenteken"];
    $location_id = $_GET["location_id"];
    $result = insertTraffic($conn, $location_id, $plate);

    if ($result) {
        $NotificationService->Process($plate, $location_id);

        echo json_encode($result);
    } else {
        echo json_encode(array("error" => "Kenteken niet gevonden"));
    }
} else {
    echo json_encode(array("error" => "Kenteken niet meegegeven"));
}

$conn->close();
?>
