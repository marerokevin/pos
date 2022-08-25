<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    // $supId = $_POST['supId'];
    $cardNum = $_POST['userCard'];
    $userName = strtolower($_POST['userName']);
    $cashierName = $_POST['cashierName'];
    $userRole = $_POST['userRole'];
    $userPass = password_hash($_POST['userPass'], PASSWORD_DEFAULT);

    $checkExisting = "SELECT * FROM `users` WHERE `user_name` = '$userName'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAddUsername'] = true;
        header("location: users.php");
    }else{
        $checkCardExisting = "SELECT * FROM `users` WHERE `user_rfid` = '$cardNum'";
        $resultCardExisting = mysqli_query($con, $checkCardExisting);
        if(mysqli_num_rows($resultCardExisting) > 0){
            $_SESSION['errorAddCard'] = true;
            header("location: users.php");
        }else{
            $insertUser = "INSERT INTO `users`(`user_id`, `user_name`, `user_pass`, `cashier_name`, `user_rfid`, `role`, `avail_amount`) VALUES (null,'$userName','$userPass','$cashierName','$cardNum','$userRole', '1000')";
            mysqli_query($con, $insertUser);
            $_SESSION['successAdd'] = true;
            header("location: users.php");
        }
    }

?>