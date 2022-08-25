<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newSafe = $_POST['inputSafe'];

    $updateSafe = "UPDATE `admin_settings` SET `safe_stock`='$newSafe' WHERE set_id='1'";
    mysqli_query($con, $updateSafe);

    $_SESSION['successSafe'] = true;
    $_SESSION['safe_stock'] = $newSafe;
    header('location: settings.php');
?>