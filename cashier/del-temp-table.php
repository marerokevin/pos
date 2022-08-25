<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $_SESSION['searchVal'] = "";

    $deleteTempItem = "TRUNCATE `temp_item`";
    mysqli_query($con, $deleteTempItem);
    $_SESSION['deleteAllSuccess'] = true;
    header("location: home.php");


?>