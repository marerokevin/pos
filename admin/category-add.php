<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }
    
    // $catId = $_POST['CatId'];
    $catName = strtolower($_POST['catName']);

    $checkExisting = "SELECT * FROM `category` WHERE `cat_name` = '$catName'";
    $resultExisting = mysqli_query($con, $checkExisting);
    if(mysqli_num_rows($resultExisting) > 0){
        $_SESSION['errorAdd'] = true;
        header("location: category.php");
    }else{
        $insertCat = "INSERT INTO `category`(`cat_id`, `cat_name`) VALUES (null,'$catName')";
        mysqli_query($con, $insertCat);
        $_SESSION['successAdd'] = true;
        header("location: category.php");
    }

?>