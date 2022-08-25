<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newTheme = $_POST['systemTheme'];

    $updateTheme = "UPDATE `admin_settings` SET `Theme`='$newTheme' WHERE set_id='1'";
    mysqli_query($con, $updateTheme);

    $_SESSION['successTheme'] = true;
    $_SESSION['sysTheme'] = $newTheme;
    header('location: settings.php');
?>