<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $ItemDesc = $_POST['aeItemDesc'];
    $ItemRP = $_POST['aeItemRP'];
    $ItemWP = $_POST['aeItemWP'];
    $ItemCat = ucwords($_POST['aeItemCat']);
    $ItemSup = $_POST['aeItemSup'];

    $dateUpdated = date("Y-m-d");
    $updatedBy = $_SESSION['cashier_name'];

    $barcode = $_POST['barcode'];
    if($barcode == 'with-barcode'){
        $itemCode = $_POST['aeItemCode'];

        $addItem = "INSERT INTO `item_with_barcode`(`item_id`, `item_code`, `item_name`, `item_retail_price`, `item_stock`, `item_category`, `item_supplier`, `date_updated`, `updated_by`, `item_remarks`, `item_wholesale_price`) VALUES (null,'$itemCode','$ItemDesc','$ItemRP','0','$ItemCat','$ItemSup','$dateUpdated','$updatedBy','Added','$ItemWP')";
        mysqli_query($con, $addItem);
    }else{

        if($_FILES['aeItemImage']['name'] != ""){
            $file = $_FILES['aeItemImage'];
   
            $fileName = $_FILES['aeItemImage']['name'];
            $fileTmpName = $_FILES['aeItemImage']['tmp_name'];
            $fileType = $_FILES['aeItemImage']['type'];

            $fileTempExt = explode('.', $fileName);
            $fileExt = strtolower(end($fileTempExt));

            $newFileName = uniqid('', true).".".$fileExt;
            $fileDest = "../images/items/".$newFileName;
            
            move_uploaded_file($fileTmpName, $fileDest);
        }else{
            $fileDest = "../images/items/default-image.png";
        }

        $addItem = "INSERT INTO `item_no_barcode`(`item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_stock`, `itemnb_category`, `itemnb_suppplier`, `date_updated`, `updated_by`, `itemnb_remarks`, `itemnb_img`, `itemnb_wholesale_price`) VALUES (null,'$ItemDesc','$ItemRP','0','$ItemCat','$ItemSup','$dateUpdated','$updatedBy','Added','$fileDest','$ItemWP')";
        mysqli_query($con, $addItem);
    }

    $_SESSION['addItemSuccess'] = true;
    header("location: inventory.php");
?>