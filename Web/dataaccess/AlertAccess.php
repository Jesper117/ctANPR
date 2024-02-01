<?php
require_once("../dataaccess/Database.php");

class AlertAccess
{
    private $Database;
    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetAlerts($UserId)
    {
        $sql = "SELECT * FROM alerts WHERE user_id = " . $UserId . " ORDER BY created_at DESC";
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

    public function GetAlertByUserId($UserId, $Id)
    {
        $sql = "SELECT * FROM alerts WHERE user_id = " . $UserId . " AND id = " . $Id ;
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

    public function GetAlertsByPlate($Plate)
    {
        $sql = "SELECT * FROM alerts WHERE kenteken = '" . $Plate . "'";
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

    public function CreateAlert($Name, $Plate, $UserId, $Type)
    {
        $sql = "INSERT INTO alerts (name, kenteken, user_id, type) VALUES (?, ?, ?, ?)";
        $insert = $this->Database->query($sql, $Name, $Plate, $UserId, $Type);

        return $insert->affectedRows();
    }

    public function RemoveAlert($Id)
    {
        $sql = "DELETE FROM alerts WHERE id = ?";
        $delete = $this->Database->query($sql, $Id);

        return $delete->affectedRows();
    }

}
?>