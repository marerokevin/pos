<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $userId = $_POST['userId'];
    $cardNum = $_POST['userCard'];
    $userName = strtolower($_POST['userName']);
    $cashierName = $_POST['cashierName'];
    $userRole = $_POST['userRole'];

    $checkExisting = "SELECT * FROM `users` WHERE `user_name` = '$userName' AND `user_id` != '$userId'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAddUsername'] = true;
        header("location: users.php");
    }else{
        $checkCardExisting = "SELECT * FROM `users` WHERE `user_rfid` = '$cardNum' AND `user_id` != '$userId'";
        $resultCardExisting = mysqli_query($con, $checkCardExisting);
        if(mysqli_num_rows($resultCardExisting) > 0){
            $_SESSION['errorAddCard'] = true;
            header("location: users.php");
        }else{
            if($_POST['userPass'] == ""){
                $updateUser = "UPDATE `users` SET `user_name`='$userName',`cashier_name`='$cashierName',`user_rfid`='$cardNum',`role`='$userRole' WHERE `user_id` = '$userId'";
            }else{
                $userPass = password_hash($_POST['userPass'], PASSWORD_DEFAULT);
                $updateUser = "UPDATE `users` SET `user_name`='$userName',`user_pass`='$userPass',`cashier_name`='$cashierName',`user_rfid`='$cardNum',`role`='$userRole' WHERE `user_id` = '$userId'";
            }
            mysqli_query($con, $updateUser);
            $_SESSION['successEdit'] = true;
            header("location: users.php");
        }
    }

?>