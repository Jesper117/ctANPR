<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION["user"])) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">

    <title>ctANPR</title>
    <link rel="icon" href="img/calendar.png">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link rel="stylesheet" href="css/login.css">
</head>

<body>

<div class="logo">
    <img draggable="false" src="img/logo.png" alt="ctANPR">
</div>

<form class="login" method="POST" action="../interface/login.php">
    <fieldset>
        <legend class="legend">Login</legend>

        <div class="input">
            <input id="username-input" name="username" type="text" placeholder="Gebruikersnaam" required/>
            <span><i class="fa fa-user-o"></i></span>
        </div>

        <div class="input">
            <input id="password-input" name="password" type="password" placeholder="Wachtwoord" required/>
            <span><i class="fa fa-lock"></i></span>
        </div>

        <button type="submit" class="submit"><i class="fa fa-long-arrow-right"></i></button>

    </fieldset>

    <div id="feedback-div" class="feedback negative">
        <label id="login-callback-display"></label>
    </div>

    <script src="js/login.js"></script>

    <?php
    if (isset($_SESSION["login_callback"])) {
        $login_callback = $_SESSION["login_callback"];
        unset($_SESSION["login_callback"]);

        $Success = $login_callback["success"];
        $Message = $login_callback["message"];

        echo "<script>DisplayCallback('$Success', '$Message');</script>";

        if ($Success) {
            header("Location: dashboard.php");
            exit();
        }
    }
    ?>
</form>
</body>
</html>