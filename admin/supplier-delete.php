<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $supId = $_POST['delSupId'];

    $deleteSup = "DELETE FROM `supplier` WHERE `sup_id`='$supId'";
    mysqli_query($con, $deleteSup);
    
    $_SESSION['successDelete'] = true;
    header("location: supplier.php");

?>