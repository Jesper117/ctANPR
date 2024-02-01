<?php
require_once("../services/AlertService.php");
require_once("../services/PlateService.php");
require_once("../services/VehicleService.php");
require_once("../services/TrafficService.php");

$AlertService = new AlertService;
$PlateService = new PlateService;
$VehicleService = new VehicleService;
$TrafficService = new TrafficService;

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$StartDate = date("Y-m-d H:i:s", strtotime("-1 month"));
$LatestHits = $AlertService->GetHitsAfterDateFromUserId($_SESSION["user"], $StartDate, 10);

$StatisticsInfoText = "";
$RowCount = 0;
if (is_array($LatestHits)) {
    $RowCount = count($LatestHits);
}

if ($RowCount == 0) {
    echo "<p style='margin-left: 20px;'>Er hebben nog geen ANPR-hits plaatsgevonden.</p>";
} else {
    for ($i = 0; $i < $RowCount; $i++) {
        $TrafficRow = $LatestHits[$i];
        $Plate = $TrafficRow["kenteken"];

        $LocationName = $TrafficRow["name"];

        $DateTimeFormat = date("d/m/Y - H:i:s", strtotime($TrafficRow["created_at"]));

        $DisplayPlate = $PlateService->FormatLicensePlate($Plate);
        $VehicleInfo = $VehicleService->GetVehicleByPlate($Plate);

        $LoweredBrand = strtolower($VehicleInfo["Merk"]);
        $BrandLogo = "img/brands/$LoweredBrand.png";

        $Handelsbenaming = strtolower($VehicleInfo["Handelsbenaming"]);;
        $DisplayVehicleType = ucwords($LoweredBrand . " " . $Handelsbenaming);

        if (strtolower(explode(" ", $Handelsbenaming)[0]) == strtolower($VehicleInfo["Merk"])) {
            $DisplayVehicleType = ucwords($Handelsbenaming);
        }

        if (strlen($DisplayVehicleType) > 20) {
            $DisplayVehicleType = substr($DisplayVehicleType, 0, 19) . "...";
        }

        $NotableHit = false;
        $NotableHitInfo = "";
        $NotableHitColor = "#e34747";
        $NotableHitTextColor = "#ad3939";

        $ANPR_Hit_Info = $TrafficService->HitCheck($_SESSION["user"], $Plate);
        if ($ANPR_Hit_Info) {
            $NotableHit = true;
            $NotableHitInfo = "ANPR HIT - " . $ANPR_Hit_Info["name"];

            if ($ANPR_Hit_Info["type"] == "1") {
                $NotableHitColor = "#e34747";
                $NotableHitTextColor = "#ad3939";
            } elseif ($ANPR_Hit_Info["type"] == "2") {
                $NotableHitColor = "#d2bc4b";
                $NotableHitTextColor = "#b09d3e";
            } elseif ($ANPR_Hit_Info["type"] == "3") {
                $NotableHitColor = "#477de3";
                $NotableHitTextColor = "#2d5fbc";
            }
        }


        echo "<div class='transfer'>";

        if ($NotableHit) {
            echo "<div style='background-color: $NotableHitColor' class='transfer-logo'>";
        } else {
            echo "<div class='transfer-logo'>";
        }
        echo "<img draggable='false' src='$BrandLogo'/>";
        echo "</div>";

        if ($NotableHit) {
            echo "<dl style='background-color: $NotableHitColor' class='transfer-details alerts'>";
        } else {
            echo "<dl class='transfer-details alerts'>";
        }
        echo "<div>";
        echo "<dt>$DisplayVehicleType</dt>";
        echo "</div>";
        echo "<div>";
        echo "<dt>$DisplayPlate</dt>";
        echo "</div>";
        echo "<div>";
        echo "<dt>$LocationName</dt>";
        echo "</div>";
        echo "<div>";
        echo "<dt>$DateTimeFormat</dt>";
        echo "</div>";

        if ($NotableHit) {
            echo "<div>";
            echo "<dt style='padding: 5px; background-color: $NotableHitTextColor'>$NotableHitInfo</dt>";
            echo "</div>";
        }

        echo "</dl>";
        echo "</div>";
    }
}
?>