<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newMsg = $_POST['inputMsg'];
    $enewMsg = mysqli_real_escape_string($con, $newMsg);

    $updateMsg = "UPDATE `admin_settings` SET `reciept_msg`='$enewMsg' WHERE set_id='1'";
    mysqli_query($con, $updateMsg);

    $_SESSION['successMsg'] = true;
    $_SESSION['reciept_msg'] = $newMsg;
    header('location: settings.php');
?>