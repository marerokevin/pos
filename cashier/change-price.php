<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $itemCode = $_GET['itemCode'];

    $dateNow = date('Y-m-d');
    $cName = $_SESSION['cashier_name'];

    if(isset($_GET['newRetailPrice'])){

        $newPrice = $_GET['newRetailPrice'];

        $updatePrice = "UPDATE `item_with_barcode` SET `item_retail_price` = '$newPrice', `date_updated` = '$dateNow', `updated_by` = '$cName' , `item_remarks` = 'Changed Retail Price' WHERE `item_code` = '$itemCode'";
        mysqli_query($con, $updatePrice);
    
    
        $updatePrice = "UPDATE `item_no_barcode` SET `itemnb_retail_price` = '$newPrice', `date_updated`='$dateNow',`updated_by`='$cName', `itemnb_remarks`='Changed Retail Price' WHERE `item_code` = '$itemCode'";
        mysqli_query($con, $updatePrice);
    
        $_SESSION['updateSuccessful'] = true;
        header("location: inventory.php");
    }

    if(isset($_GET['newWholesalePrice'])){

        $newPrice = $_GET['newWholesalePrice'];

        $updatePrice = "UPDATE `item_with_barcode` SET `item_wholesale_price` = '$newPrice',`date_updated`='$dateNow',`updated_by`='$cName',`item_remarks`='Changed Retail Price' WHERE `item_code` = '$itemCode'";
        mysqli_query($con, $updatePrice);
    
    
        $updatePrice = "UPDATE `item_no_barcode` SET `itemnb_wholesale_price` = '$newPrice',`date_updated`='$dateNow',`updated_by`='$cName',`itemnb_remarks`='Changed Retail Price' WHERE `item_code` = '$itemCode'";
        mysqli_query($con, $updatePrice);
    
        $_SESSION['updateSuccessful'] = true;
        header("location: inventory.php");
    }





?>