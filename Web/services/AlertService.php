<?php
require_once("../services/SafetyService.php");
require_once("../dataaccess/AlertAccess.php");
require_once("../dataaccess/TrafficAccess.php");
require_once("../services/TrafficService.php");

class AlertService
{
    private $SafetyService;
    private $AlertAccess;
    private $TrafficAccess;
    private $TrafficService;
    public function __construct()
    {
        $this->SafetyService = new SafetyService;
        $this->AlertAccess = new AlertAccess;
        $this->TrafficAccess = new TrafficAccess;
        $this->TrafficService = new TrafficService;
    }

    public function GetAlertsByUserId($UserId)
    {
        $this->SafetyService->StringCheck($UserId);

        $Result = $this->AlertAccess->GetAlerts($UserId);
        return $Result;
    }

    public function GetHitsAfterDateFromUserId($UserId, $StartDate, $RowCount)
    {
        $this->SafetyService->StringCheck($UserId);
        $this->SafetyService->StringCheck($StartDate);

        $AllTraffic = $this->TrafficAccess->GetTrafficAfterDate($StartDate, $RowCount);

        $Hits = array();
        if ($AllTraffic) {
            $TrafficCount = count($AllTraffic);
            for ($i = 0; $i < $TrafficCount; $i++)
            {
                $Traffic = $AllTraffic[$i];
                $Hit = $this->TrafficService->HitCheck($UserId, $Traffic["kenteken"]);
                if ($Hit)
                {
                    array_push($Hits, $Hit);
                }
            }
        } else {
            return $Hits;
        }

        return $Hits;
    }

    public function GetAlertsByPlate($Plate)
    {
        $this->SafetyService->StringCheck($Plate);

        $Result = $this->AlertAccess->GetAlertsByPlate($Plate);
        return $Result;
    }

    public function CreateAlert($Name, $Plate, $UserId, $Type)
    {
        $this->SafetyService->StringCheck($Name);
        $this->SafetyService->StringCheck($Plate);
        $this->SafetyService->StringCheck($UserId);
        $this->SafetyService->StringCheck($Type);

        $Plate = str_replace(" ", "", $Plate);
        $Plate = str_replace("-", "", $Plate);

        $Plate = strtoupper($Plate);

        $Result = $this->AlertAccess->CreateAlert($Name, $Plate, $UserId, $Type);
        return $Result;
    }

    public function RemoveAlert($UserId, $Id)
    {
        $this->SafetyService->StringCheck($UserId);
        $this->SafetyService->StringCheck($Id);

        $UserOwnsAlert = $this->AlertAccess->GetAlertByUserId($UserId, $Id);
        if ($UserOwnsAlert)
        {
            $Result = $this->AlertAccess->RemoveAlert($Id);
            return $Result;
        } else {
            return false;
        }
    }
}

?>