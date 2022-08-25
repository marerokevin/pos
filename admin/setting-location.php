<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newLoc = $_POST['inputLocation'];
    echo $newLoc;

    $updateLoc = "UPDATE `admin_settings` SET `branch_location`='$newLoc' WHERE set_id='1'";
    mysqli_query($con, $updateLoc);

    $_SESSION['successLoc'] = true;
    $_SESSION['branch_loc'] = $newLoc;
    header('location: settings.php');
?>