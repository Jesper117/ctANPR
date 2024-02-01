<?php
require_once("../dataaccess/Database.php");
class UserAccess
{
    private $Database;
    public function __construct()
    {
        $this->Database = new db();
    }

    public function GetUserByUsername($Username)
    {
        $sql = "SELECT * FROM users WHERE username = '$Username'";
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

    public function GetUserById($UserId)
    {
        $sql = "SELECT * FROM users WHERE id = '$UserId'";
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