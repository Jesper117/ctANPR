<?php
require_once("../services/SafetyService.php");
require_once("../dataaccess/LocationAccess.php");
require_once("../dataaccess/UserAccess.php");
require_once("../services/PlateService.php");
require_once("../services/AlertService.php");


require("Twilio/src/Twilio/autoload.php");

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Twilio\Rest\Client;

require("PHPMailer/src/Exception.php");
require("PHPMailer/src/PHPMailer.php");
require("PHPMailer/src/SMTP.php");


class NotificationService
{
    private $SafetyService;
    private $LocationAccess;
    private $UserAccess;
    private $PlateService;
    private $AlertService;

    public function __construct()
    {
        $this->SafetyService = new SafetyService();
        $this->LocationAccess = new LocationAccess();
        $this->UserAccess = new UserAccess();
        $this->PlateService = new PlateService();
        $this->AlertService = new AlertService();
    }

    private function SendEmail($to, $subject, $body, $attachments)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = "smtp.office365.com";
            $mail->SMTPAuth = true;
            $mail->Username = "ctanpr@hotmail.com";
            $mail->Password = "9rZ23WxznuchaMrAexBLGQ6hptTzqR3hpZVcTCkqx3";
            $mail->SMTPSecure = "tls";
            $mail->Port = 587;

            $mail->setFrom($mail->Username, "ctANPR");
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;

            $mail->Body = $body;

            if ($attachments && count($attachments) > 0) {
                foreach ($attachments as $attachment) {
                    $mail->addStringAttachment(base64_decode($attachment["data"]), $attachment["name"], "base64", $attachment["type"]);
                }
            }

            $mail->send();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    private function SendSMS($Body, $Phone)
    {
        $sid = "AC3b34dc4138ea4dd7b01606b14b1c9241";
        $token = "8358073f0dc1dd83e11f8e95f5e9f749";
        $twilio = new Client($sid, $token);

        $message = $twilio->messages
            ->create($Phone,
                array(
                    "from" => "+13155710385",
                    "body" => $Body
                )
            );
    }

    private function HitEmail($Alert, $UserId, $LocationId)
    {
        $this->SafetyService->StringCheck($UserId);
        $User = $this->UserAccess->GetUserById($UserId);
        $Location = $this->LocationAccess->GetLocationById($LocationId);


        $Email = $User["email"];
        $Username = $User["username"];

        $AlertDate = date("d/m/Y", strtotime($Alert["created_at"]));
        $AlertTime = date("H:i:s", strtotime($Alert["created_at"]));
        $AlertName = $Alert["name"];

        $AlertPlate = $Alert["kenteken"];
        $AlertPlateDisplay = $this->PlateService->FormatLicensePlate($AlertPlate);

        $AlertRoad = $Location["road"];
        $AlertLocation = $Location["name"];
        $AlertDisplayName = $AlertRoad . ", " . $AlertLocation;

        $Body = file_get_contents("http://templates.codecove.nl/ctANPR_priority_alert.html");

        $Body = str_replace("[[VOORNAAM]]", $Username, $Body);
        $Body = str_replace("[[DATUM]]", $AlertDate, $Body);
        $Body = str_replace("[[TIJD]]", $AlertTime, $Body);
        $Body = str_replace("[[ALERTNAAM]]", $AlertName, $Body);
        $Body = str_replace("[[WEG]]", $AlertDisplayName, $Body);
        $Body = str_replace("[[KENTEKEN]]", $AlertPlateDisplay, $Body);

        $Success = $this->SendEmail($Email, "ANPR-HIT: $AlertName", $Body, null);

        return $Success;
    }

    private function HitSMS($Alert, $UserId, $LocationId)
    {
        $this->SafetyService->StringCheck($UserId);
        $User = $this->UserAccess->GetUserById($UserId);
        $Location = $this->LocationAccess->GetLocationById($LocationId);


        $Email = $User["email"];
        $Username = $User["username"];

        $AlertDate = date("d/m/Y", strtotime($Alert["created_at"]));
        $AlertTime = date("H:i:s", strtotime($Alert["created_at"]));
        $AlertName = $Alert["name"];

        $AlertPlate = $Alert["kenteken"];
        $AlertPlateDisplay = $this->PlateService->FormatLicensePlate($AlertPlate);

        $AlertRoad = $Location["road"];
        $AlertLocation = $Location["name"];
        $AlertDisplayName = $AlertRoad . ", " . $AlertLocation;

        $AMPMString = date("A", strtotime($AlertTime));

        $Body = ".\n\n\nANPR-HIT:\n\nAlertnaam: $AlertName\nKenteken: $AlertPlateDisplay\nLocatie: $AlertDisplayName\nDatum: $AlertDate om $AlertTime $AMPMString";

        $this->SendSMS($Body, $User["phone"]);
    }

    public function Process($Plate, $LocationId)
    {
        $this->SafetyService->StringCheck($Plate);

        $Plate = strtoupper($Plate);
        $Alerts = $this->AlertService->GetAlertsByPlate($Plate);

        if ($Alerts && count($Alerts) > 0) {
            $AlertCount = count($Alerts);
            for ($i = 0; $i < $AlertCount; $i++) {
                $Alert = $Alerts[$i];
                $UserId = $Alert["user_id"];

                if ($Alert["type"] == "1") {
                    $this->HitEmail($Alert, $UserId, $LocationId);
                    $this->HitSMS($Alert, $UserId, $LocationId);
                } else if ($Alert["type"] == "2") {
                    $this->HitSMS($Alert, $UserId, $LocationId);
                }
            }
        }
    }
}

?>