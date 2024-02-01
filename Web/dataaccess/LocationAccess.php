<?php
require_once("../dataaccess/Database.php");

class LocationAccess
{
    private $Database;
    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetAllLocations()
    {
        $sql = "SELECT * FROM locations";
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

    public function GetLocationById($Id)
    {
        $sql = "SELECT * FROM locations WHERE id = '$Id'";
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