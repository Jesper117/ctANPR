<?php
require_once("../services/SafetyService.php");
require_once("../dataaccess/TrafficAccess.php");
require_once("../dataaccess/AlertAccess.php");

class TrafficService
{
    private $SafetyService;
    private $TrafficAccess;
    private $AlertAccess;
    public function __construct()
    {
        $this->SafetyService = new SafetyService;
        $this->TrafficAccess = new TrafficAccess;
        $this->AlertAccess = new AlertAccess;
    }

    public function GetTrafficByPlate($Plate, $Rows)
    {
        return $this->TrafficAccess->GetTrafficByPlate($Plate, $Rows);
    }

    public function GetTrafficByLocation($Location, $Rows)
    {
        return $this->TrafficAccess->GetTrafficByLocation($Location, $Rows);
    }

    public function HitCheck($UserId, $Plate)
    {
        $this->SafetyService->StringCheck($UserId);
        $this->SafetyService->StringCheck($Plate);

        $Alerts = $this->AlertAccess->GetAlerts($UserId);

        if ($Alerts)
        {
            $AlertCount = count($Alerts);
            for ($i = 0; $i < $AlertCount; $i++)
            {
                $Alert = $Alerts[$i];

                if ($Alert["kenteken"] == $Plate)
                {
                    return $Alert;
                }
            }
        } else {
            return false;
        }
    }
}

?>