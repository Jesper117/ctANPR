<?php
require_once("../services/TrafficService.php");
require_once("../services/LocationService.php");
require_once("../services/PlateService.php");
require_once("../services/VehicleService.php");

$TrafficService = new TrafficService;
$LocationService = new LocationService;
$PlateService = new PlateService;
$VehicleService = new VehicleService;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET["location_id"])) {
    $Location = $LocationService->GetLocationById($_GET["location_id"]);

    if (!$Location) {
        header("Location: dashboard.php");
        exit();
    }
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">

    <title>ctANPR</title>
    <link rel="icon" href="img/cctv.png">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link rel="stylesheet" href="css/dashboard.css">
</head>

<body>
<div class="app">
    <header class="app-header">
        <div class="app-header-logo">
            <div class="logo">
                <h1 class="logo-title">
                    <span>ctANPR</span>
                    <span>Convolutional Traffic ANPR</span>
                </h1>
            </div>
        </div>

        <div class="app-header-navigation">
            <div class="tabs">
                <a href="#" class="active">
                    Dashboard
                </a>
            </div>
        </div>

        <div class="app-header-user">
            <img draggable="false" class="logo headinglogo" src="img/logo.png">
    </header>

    <div class="app-body">
        <div class="app-body-navigation">
            <nav class="navigation">
                <a href="dashboard.php">
                    <i class="ph-browsers"></i>
                    <span>Locaties</span>
                </a>
                <a href="alerts.php">
                    <i class="ph-check-square"></i>
                    <span>Alerts</span>
                </a>
            </nav>
        </div>

        <div class="app-body-main-content">
            <?php
            $Traffic = $TrafficService->GetTrafficByLocation($_GET["location_id"], 100);

            $StatisticsInfoText = "";
            $RowCount = 0;
            if (is_array($Traffic)) {
                $RowCount = count($Traffic);
                $StatisticsInfoText = "Statistieken van de laatste $RowCount voertuigen.";
                if ($RowCount == 0) {
                    $StatisticsInfoText = "Er zijn nog geen voertuigen gedetecteerd.";
                } else if ($RowCount == 1) {
                    $StatisticsInfoText = "Statistieken van het laatste voertuig.";
                }
            } else {
                $StatisticsInfoText = "Er zijn nog geen voertuigen gedetecteerd.";
            }

            $LocationName = $Location["name"];

            echo "<h1 class='location-title'>ANPR Locatie $LocationName 📸</h1>";
            echo "<p class='sidetext'>$StatisticsInfoText</p>";
            ?>
            <section class="service-section">
                <canvas id="brand_statistics"></canvas>
                <canvas id="fuel_statistics"></canvas>
            </section>

            <section class="transfer-section">
                <div class="transfer-section-header">
                    <h2>Hits</h2>
                    <div class="filter-options">
                        <?php
                        $DetectionText = "Alle gedetecteerde voertuigen.";
                        if ($RowCount == 0) {
                            $DetectionText = "Er zijn nog geen voertuigen gedetecteerd.";
                        }

                        echo "<p>$DetectionText</p>";
                        ?>
                    </div>
                </div>
                <div class="transfers"></div>
            </section>
        </div>
        <div class="app-body-sidebar">
            <section class="payment-section">
                <h2>Technische incidenten</h2>
                <div class="payments">
                    <div class="payment">
                        <div class="card red">
                            <span>ANPR Camera</span>
                            <span>Storing</span>
                        </div>
                        <div class="payment-details">
                            <h3>Groot incident <br> 20/09/2023 - 11:00 t/m 12:30</h3>
                            <span>Opgelost ✅</span>
                        </div>
                    </div>
                    <div class="payment">
                        <div class="card yellow">
                            <span>API</span>
                            <span>Storing</span>
                        </div>
                        <div class="payment-details">
                            <h3>Klein incident <br> 20/09/2023 - 10:27 t/m 10:34</h3>
                            <span>Opgelost ✅</span>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
</body>


<script>
    function UpdateHits() {
        $(".transfers").load("../views/location_hits.php?location_id=<?php echo $_GET["location_id"]; ?>");
    }

    UpdateHits();
    setInterval(UpdateHits, 2000);
</script>
</html>