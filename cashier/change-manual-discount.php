<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $manDisc = $_GET['manDisc'];
    echo $manDisc;

    $updateDisc = "UPDATE `admin_settings` SET `discount`='$manDisc' WHERE `set_id` = '1'";
    mysqli_query($con, $updateDisc);
    $_SESSION['discUpdateSuccessful'] = true;
    header("location: home.php");

?>