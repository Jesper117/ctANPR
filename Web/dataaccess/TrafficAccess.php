<?php
require_once("../dataaccess/Database.php");

class TrafficAccess
{
    private $Database;
    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetTrafficByPlate($Plate, $Rows)
    {
        $sql = "SELECT * FROM traffic WHERE kenteken = " . $Plate . " ORDER BY created_at DESC LIMIT " . $Rows;
        $result = $this->Database->query($sql)->fetchAll();

        if ($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function GetTrafficAfterDate($Date, $Rows)
    {
        $sql = "SELECT * FROM traffic WHERE created_at > '" . $Date . "' ORDER BY created_at DESC LIMIT " . $Rows;
        $result = $this->Database->query($sql)->fetchAll();

        if ($result)
        {
            return $result;
        }
        else
        {
            return false;
        }
    }

    public function GetTrafficByLocation($Location, $Rows)
    {
        $sql = "SELECT * FROM traffic WHERE location_id = " . $Location . " ORDER BY created_at DESC LIMIT " . $Rows;
        $result = $this->Database->query($sql)->fetchAll();

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