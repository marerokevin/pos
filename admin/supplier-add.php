<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    // $supId = $_POST['supId'];
    $supName = strtolower($_POST['supName']);
    $supCon = $_POST['supCon'];

    $checkExisting = "SELECT * FROM `supplier` WHERE `sup_name` = '$supName'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAdd'] = true;
        header("location: supplier.php");
    }else{
        $insertSup = "INSERT INTO `supplier`(`sup_id`, `sup_name`, `sup_cont_num`) VALUES (null,'$supName','$supCon')";
        mysqli_query($con, $insertSup);
        $_SESSION['successAdd'] = true;
        header("location: supplier.php");
    }

?>