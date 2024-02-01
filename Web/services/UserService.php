<?php
require_once("../dataaccess/UserAccess.php");

class UserService
{
    private $UserAccess;

    public function __construct()
    {
        $this->UserAccess = new UserAccess();
    }


    public function Login($Username, $Password)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $User = $this->UserAccess->GetUserByUsername($Username);

        if ($User) {
            if (password_verify($Password, $User["password"])) {
                $_SESSION["user"] = $User["id"];

                return true;
            } else {
                $_SESSION["login_callback"] = [
                    "success" => false,
                    "message" => "Incorrecte gegevens."
                ];

                return false;
            }
        } else {
            $_SESSION["login_callback"] = [
                "success" => false,
                "message" => "Incorrecte gegevens."
            ];

            return false;
        }
    }

    public function GetUserById($UserId)
    {
        return $this->UserAccess->GetUserById($UserId);
    }
}

?>