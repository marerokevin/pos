<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $cardNum = $_POST['inputCard'];
    $curDate = date('Y-m-d');
    $curTime = date('H:i:s');
    $_SESSION['curTime'] = $curTime;

    $getUser = "SELECT * FROM `users` WHERE `user_rfid` = '$cardNum'";
    $resultUser = mysqli_query($con, $getUser);
    if(mysqli_num_rows($resultUser) > 0){
        $rowUser = mysqli_fetch_assoc($resultUser);
        $userName = ucwords($rowUser['cashier_name']);
        $_SESSION['cName'] = $userName;

        $checkStatus = "SELECT * FROM `dtr` WHERE `rfid_number` = '$cardNum' AND `log_date` = '$curDate'";
        $resultStatus = mysqli_query($con, $checkStatus);
        if(mysqli_num_rows($resultStatus) > 0){
            $updateOut = "UPDATE `dtr` SET `time_out`='$curTime' WHERE `rfid_number` = '$cardNum' AND `log_date` = '$curDate'";
            mysqli_query($con, $updateOut);
            $_SESSION['status'] = 'Time Out';
            header("location: dtr.php");
        }else{

            $insertIn = "INSERT INTO `dtr`(`dtr_id`, `rfid_number`, `cashier`, `time_in`, `time_out`, `log_date`) VALUES (null,'$cardNum','$userName','$curTime',null,'$curDate')";
            mysqli_query($con, $insertIn);
            $_SESSION['status'] = 'Time In';
            header("location: dtr.php");
        }
    }else{
        $_SESSION['unregError'] = true;
        unset($_SESSION['cName']);
        unset($_SESSION['status']);
        unset($_SESSION['curTime']);
        header("location: dtr.php");
    }

?>