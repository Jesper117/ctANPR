<?php
require_once("../services/PlateService.php");

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

function fetchPlate($conn, $plate) {
    $stmt = $conn->prepare("SELECT * FROM vehicles WHERE Kenteken = ?");
    $stmt->bind_param("s", $plate);

    $start_time = microtime(true);

    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    $stmt->close();

    $end_time = microtime(true);

    $time_taken = $end_time - $start_time;
    $time_taken = round($time_taken, 4);

    $data["fetch_time"] = $time_taken;

    return $data;
}

if (isset($_GET["kenteken"], $_GET["endpoint"])) {
    $plate = $_GET["kenteken"];

    if ($_GET["endpoint"] == "format") {
        $PlateService = new PlateService();
        $FormattedPlate = $PlateService->FormatLicensePlate($plate);

        echo json_encode(array("kenteken" => $FormattedPlate));
    } elseif ($_GET["endpoint"] == "fetch") {
        $result = fetchPlate($conn, $plate);

        if ($result) {
            echo json_encode($result);
        } else {
            echo json_encode(array("error" => "Kenteken niet gevonden"));
        }
    }
} else {
    echo json_encode(array("error" => "Kenteken niet meegegeven"));
}

$conn->close();
?>
