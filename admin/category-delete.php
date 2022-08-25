<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $catId = $_POST['delCatId'];
    echo $catId;

    $deleteCat = "DELETE FROM `category` WHERE `cat_id`='$catId'";
    mysqli_query($con, $deleteCat);
    
    $_SESSION['successDelete'] = true;
    header("location: category.php");

?>