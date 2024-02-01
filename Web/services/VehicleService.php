<?php
require_once("../dataaccess/VehicleAccess.php");
require_once("../services/SafetyService.php");

class VehicleService
{
    private $SafetyService;
    private $VehicleAccess;

    public function __construct()
    {
        $this->SafetyService = new SafetyService();
        $this->VehicleAccess = new VehicleAccess();
    }


    public function GetVehicleByPlate($Plate)
    {
        $Vehicle = $this->VehicleAccess->GetVehicleByPlate($Plate);

        if ($Vehicle) {
            return $Vehicle;
        } else {
            return false;
        }
    }
}

?>