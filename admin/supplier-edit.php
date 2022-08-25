<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $supId = $_POST['supId'];
    $supName = strtolower($_POST['supName']);
    $supCon = $_POST['supCon'];

    $checkExisting = "SELECT * FROM `supplier` WHERE `sup_name` = '$supName'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAdd'] = true;
        header("location: supplier.php");
    }else{
        $updateSup = "UPDATE `supplier` SET `sup_name`='$supName',`sup_cont_num`='$supCon' WHERE `sup_id`='$supId'";
        mysqli_query($con, $updateSup);
        $_SESSION['successEdit'] = true;
        header("location: supplier.php");
    }

?>