<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newName = $_POST['inputName'];
    $enewName = mysqli_real_escape_string($con, $newName);

    $updateName = "UPDATE `admin_settings` SET `branch_name`='$enewName' WHERE set_id='1'";
    mysqli_query($con, $updateName);

    $_SESSION['successName'] = true;
    $_SESSION['branch_name'] = $newName;
    header('location: settings.php');
?>