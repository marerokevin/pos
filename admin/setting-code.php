<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newCode = $_POST['inputCode'];
    echo $newCode;

    $updateCode = "UPDATE `admin_settings` SET `reciept_code`='$newCode' WHERE set_id='1'";
    mysqli_query($con, $updateCode);

    $_SESSION['successCode'] = true;
    $_SESSION['branch_code'] = $newCode;
    header('location: settings.php');
?>