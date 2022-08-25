<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    unset($_SESSION['curCardNum']);
    unset($_SESSION['curAmount']);
    unset($_SESSION['req_name']);

    header("location: request.php");
?>