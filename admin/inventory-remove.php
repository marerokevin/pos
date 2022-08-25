<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $itemCode = $_GET['delId'];
    echo $itemCode;
    
    $removeItem = "DELETE FROM `item_no_barcode` WHERE `item_code` = $itemCode";
    mysqli_query($con, $removeItem);
    $removeItem = "DELETE FROM `item_with_barcode` WHERE `item_code` = $itemCode";
    mysqli_query($con, $removeItem);

    $_SESSION['RemoveSuccess'] = true;
    header("location: inventory.php");
?>