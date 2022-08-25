<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $catId = $_POST['catId'];
    $catName = strtolower($_POST['catName']);

    $checkExisting = "SELECT * FROM `category` WHERE `cat_name` = '$catName'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAdd'] = true;
        header("location: category.php");
    }else{
        $updateCat = "UPDATE `category` SET `cat_name`='$catName' WHERE `cat_id`='$catId'";
        mysqli_query($con, $updateCat);
        $_SESSION['successEdit'] = true;
        header("location: category.php");
    }

?>