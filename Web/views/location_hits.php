<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/ctANPR/Web/services/PlateService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ctANPR/Web/services/VehicleService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ctANPR/Web/services/TrafficService.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/ctANPR/Web/services/LocationService.php");

$PlateService = new PlateService();
$VehicleService = new VehicleService();
$TrafficService = new TrafficService();
$LocationService = new LocationService();

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

$LocationName = "";
if (isset($_GET["location_id"])) {
    $LocationID = $_GET["location_id"];
    $Location = $LocationService->GetLocationById($LocationID);

    if ($Location) {
        $LocationName = $Location["name"];
    } else {
        exit();
    }
} else {
    exit();
}

$Traffic = $TrafficService->GetTrafficByLocation($_GET["location_id"], 100);

$RowCount = 0;
if (is_array($Traffic)) {
    $RowCount = count($Traffic);
}

if ($RowCount == 0) {
    echo "<p style='margin-left: 20px;'>Er zijn nog geen voertuigen gedetecteerd.</p>";
} else {
    for ($i = 0; $i < $RowCount; $i++) {
        $TrafficRow = $Traffic[$i];
        $Plate = $TrafficRow["kenteken"];

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

        $APK_Expired = false;
        $Expiration_APK = $VehicleInfo["Vervaldatum APK"];
        if ($Expiration_APK != "Geen verstrekking in Open Data") {
            $Expiration_APK = date("Y-m-d", strtotime($Expiration_APK));
            $APK_Expired = strtotime($Expiration_APK) < strtotime("now");
        }

        $Insured = $VehicleInfo["WAM verzekerd"];
        $WOK = $VehicleInfo["Wacht op keuren"];

        if ($Insured == "Nee") {
            $NotableHit = true;
            $NotableHitInfo = "NIET VERZEKERD";
        } elseif ($WOK != "Geen verstrekking in Open Data") {
            $NotableHit = true;
            $NotableHitInfo = "WOK";
        } elseif ($APK_Expired) {
            $NotableHit = true;
            $NotableHitInfo = "APK VERLOPEN";
        }

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