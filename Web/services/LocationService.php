<?php
require_once("../dataaccess/LocationAccess.php");
require_once("../services/SafetyService.php");

class LocationService
{
    private $SafetyService;
    private $LocationAccess;
    public function __construct()
    {
        $this->SafetyService = new SafetyService();

        $this->LocationAccess = new LocationAccess();
    }

    public function GetAllLocations()
    {
        return $this->LocationAccess->GetAllLocations();
    }

    public function GetLocationById($Id)
    {
        $this->SafetyService->StringCheck($Id);

        return $this->LocationAccess->GetLocationById($Id);
    }
}

?>