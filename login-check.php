<?php
    session_start();
    include("./db/conn.php");

    $username = $_POST['username'];
    $userpass = $_POST['userpass'];

    $queryUser = "SELECT * FROM `users` WHERE user_name = '$username'LIMIT 1";
    $resultUser = mysqli_query($con, $queryUser);
    if(mysqli_num_rows($resultUser) > 0){
        $userRow = mysqli_fetch_assoc($resultUser);
        if (password_verify($userpass, $userRow['user_pass'])){
            $_SESSION['conected'] = 'true';
            if($userRow['role'] == 'admin'){
                header('Location: ./admin/dashboard.php');
                $_SESSION['cashier_name'] = $userRow['cashier_name'];
            }else{
                $_SESSION['cashier_name'] = $userRow['cashier_name'];
                header('Location: ./cashier/home.php');
            }
        } else {
            $_SESSION['errMes'] = 'true';
            header('Location: ./login.php');
        }
    }else{
        $_SESSION['errMes'] = 'true';
        header('Location: ./login.php');
    }

?>