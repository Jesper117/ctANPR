<?php
require_once("../services/AlertService.php");
require_once("../services/PlateService.php");

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
                    <span>Locaties</span>
                </a>
                <a href="alerts.php">
                    <i class="ph-check-square"></i>
                    <span style="color: white">Alerts</span>
                </a>
            </nav>
        </div>
        <div class="app-body-main-content">
            <section class="service-section">
                <h2>Alert aanmaken</h2>
                <form class="form" method="POST" action="../interface/create_alert.php">
                    <input type="text" name="name" placeholder="Naam" required>
                    <input type="text" name="plate" placeholder="Kenteken" required>

                    <div class="radio-buttons">
                        <input type="radio" id="alert" name="type" value="1">
                        <label for="alert">Priority alert</label>

                        <input type="radio" id="important" name="type" value="2" checked>
                        <label for="important">Alert</label>

                        <input type="radio" id="watch" name="type" value="3">
                        <label for="note">Watch</label>
                    </div>

                    <button type="submit">Aanmaken ➕</button>
                </form>
            </section>

            <section class="transfer-section">
                <div class="transfer-section-header">
                    <h2>Alerts</h2>
                    <div class="filter-options">
                        <p>Al je ingestelde alerts.</p>
                    </div>
                </div>
                <div class="transfers">
                    <?php
                    $AlertService = new AlertService();
                    $PlateService = new PlateService();

                    $Alerts = $AlertService->GetAlertsByUserId($_SESSION["user"]);

                    if ($Alerts) {
                        foreach ($Alerts as $Alert) {
                            $FormattedPlate = $PlateService->FormatLicensePlate($Alert["kenteken"]);

                            $AlertId = $Alert["id"];

                            $AlertType = "";
                            $Icon = "";
                            if ($Alert["type"] == "1") {
                                $AlertType = "Priority alert";
                                $Icon = "img/alert.png";
                            } else if ($Alert["type"] == "2") {
                                $AlertType = "Alert";
                                $Icon = "img/warning.png";
                            } else if ($Alert["type"] == "3") {
                                $AlertType = "Watch";
                                $Icon = "img/cctv.png";
                            }

                            echo "<div class=\"transfer\">";
                            echo "<div class=\"transfer-logo\">";
                            echo "<img draggable=\"false\" src=\"" . $Icon . "\">";
                            echo "</div>";
                            echo "<dl class=\"transfer-details alerts\">";
                            echo "<div>";
                            echo "<dt>" . $Alert["name"] . "</dt>";
                            echo "</div>";
                            echo "<div>";
                            echo "<dt>" . $AlertType . "</dt>";
                            echo "</div>";
                            echo "<div>";
                            echo "<dt>" . $FormattedPlate . "</dt>";
                            echo "</div>";
                            echo "<div>";
                            echo "<a href='../interface/remove_alert.php?id=$AlertId'  class=\"remove-btn\">❌️</a>";
                            echo "</div>";
                            echo "</dl>";
                            echo "</div>";
                        }
                    } else {
                        echo "<p>Geen alerts gevonden.</p>";
                    }
                    ?>
                </div>
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
</html>