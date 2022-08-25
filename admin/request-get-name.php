<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $userCard = $_POST['userCard'];

    $checkAmount = "SELECT * FROM `users` WHERE `user_rfid` = '$userCard'";
    $resultAmount = mysqli_query($con, $checkAmount);
    if(mysqli_num_rows($resultAmount) > 0){
        $rowAmount = mysqli_fetch_assoc($resultAmount);
        $curAmount = $rowAmount['avail_amount'];
        if($curAmount > 0){
            $_SESSION['curCardNum'] = $userCard;
            $_SESSION['curAmount'] = $curAmount;
            $_SESSION['req_name'] = $rowAmount['cashier_name'];
            header("location: request.php");
        }else{
            $_SESSION['amountError'] = true;
            header("location: request.php");
        }
    }else{
        $_SESSION['userError'] = true;
        header("location: request.php");
    }
?>