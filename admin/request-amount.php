<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $userCard = $_SESSION['curCardNum'];
    $userName = $_SESSION['req_name'];
    $reqAmount = $_POST['reqAmount'];
    $dateNow = date('Y-m-d');
    $timeNow = date('H:i:s');

    $insertTran = "INSERT INTO `req_tran`(`req_id`, `user_card`, `user_name`, `req_amount`, `req_date`, `req_time`) VALUES (null,'$userCard','$userName','$reqAmount','$dateNow','$timeNow')";
    mysqli_query($con, $insertTran);

    $updateAmount = "UPDATE `users` SET `avail_amount`= (`avail_amount` - $reqAmount) WHERE `user_rfid` = '$userCard'";
    mysqli_query($con, $updateAmount);

    unset($_SESSION['curCardNum']);
    unset($_SESSION['curAmount']);
    unset($_SESSION['req_name']);

    $_SESSION['reqSuccess'] = true;
    header("location: request.php");
?>