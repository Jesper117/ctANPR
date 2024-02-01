<?php
require_once("../dataaccess/Database.php");
class VehicleAccess
{
    private $Database;
    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetVehicleByPlate($Plate)
    {
        $sql = "SELECT * FROM vehicles WHERE Kenteken = '$Plate'";
        $result = $this->Database->query($sql)->fetchArray();

        if ($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }
}
?>