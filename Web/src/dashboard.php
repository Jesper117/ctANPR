<?php
require_once("../services/TrafficService.php");
require_once("../services/LocationService.php");
require_once("../services/PlateService.php");
require_once("../services/VehicleService.php");
require_once("../services/AlertService.php");

$TrafficService = new TrafficService;
$LocationService = new LocationService;
$PlateService = new PlateService;
$VehicleService = new VehicleService;
$AlertService = new AlertService;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
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
                    <span style="color: white">Locaties</span>
                </a>
                <a href="alerts.php">
                    <i class="ph-check-square"></i>
                    <span>Alerts</span>
                </a>
            </nav>
        </div>
        <div class="app-body-main-content">
            <section class="service-section">
                <h2>Locaties</h2>
                <div class="tiles">
                    <?php
                    $LocationService = new LocationService();
                    $Locations = $LocationService->GetAllLocations();

                    foreach ($Locations as $Location) {
                        if ($Location["active"] == 1) {
                            $LocationId = $Location["id"];

                            echo "<article class=\"tile\">";
                            echo "<div class=\"tile-header\">";
                            echo "<i class=\"ph-lightning-light\"></i>";
                            echo "<h3>";
                            echo "<span>📸 ANPR Camera</span>";
                            echo "<span>" . $Location["name"] . "</span>";


                            echo "<br>";

                            echo "<div class='roadinfo'>";
                            echo "<span>" . $Location["road"] . "&nbsp;</span>";

                            if ($Location["hectometer_stone"] != null) {
                                echo "<span>" . $Location["hectometer_stone"] . "</span>";
                            }

                            echo "</div>";

                            echo "</h3>";
                            echo "</div>";
                            echo "<a href=\"location.php?location_id=$LocationId\">";
                            echo "<span>Naar overzicht</span>";
                            echo "<label>➡️</label>";
                            echo "</a>";
                            echo "</article>";
                        }
                    }
                    ?>
                </div>
                <div class="service-section-footer">
                    <p>Dit zijn alle geregistreerde locaties, het kan zijn dat er een locatie is die offline staat.</p>
                </div>
            </section>

            <section class="transfer-section">
                <div class="transfer-section-header">
                    <h2>Laatste ANPR hits</h2>
                    <div class="filter-options">
                        <p>Gebaseerd op je actieve alerts.</p>
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
        $(".transfers").load("../views/dashboard_hits.php");
    }

    UpdateHits();
    setInterval(UpdateHits, 2000);
</script>
</html>