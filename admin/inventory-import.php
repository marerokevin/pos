<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $importFile = $_FILES['importFile']['tmp_name'];
    $updatedBy = $_SESSION['cashier_name'];
    
    $file = fopen($importFile, "r");

    $z = 0;
    $dateNow = date('Y-m-d');

    while(($column = fgetcsv($file, 10000, ",")) !== FALSE){

        $itemCode = $column[0];
        $itemName = $column[1];
        $itemRPrice = $column[2];
        $itemStock = $column[3];
        $itemCat = $column[4];
        $itemSup = $column[5];
        $itemImg = $column[6];
        $itemWPrice = $column[7];


        if($z != 0){
            if(is_numeric($itemImg)){
                $checkExisting = "SELECT * FROM `item_with_barcode` WHERE `item_code` = '$itemCode'";
                $resultExisting = mysqli_query($con, $checkExisting);
                if(mysqli_num_rows($resultExisting) == 0){

                    $importInvn = "INSERT INTO `item_with_barcode`(`item_id`, `item_code`, `item_name`, `item_retail_price`, `item_stock`, `item_category`, `item_supplier`, `date_updated`, `updated_by`, `item_remarks`, `item_wholesale_price`) VALUES (null,'$itemCode','$itemName','$itemRPrice','$itemStock','$itemCat','$itemSup','$dateNow','$updatedBy','Imported','$itemWPrice')";
                    mysqli_query($con, $importInvn);

                }else{
                    $delExisting = "DELETE FROM `item_with_barcode` WHERE `item_code` = '$itemCode'";
                    mysqli_query($con, $delExisting);

                    $importInvn = "INSERT INTO `item_with_barcode`(`item_id`, `item_code`, `item_name`, `item_retail_price`, `item_stock`, `item_category`, `item_supplier`, `date_updated`, `updated_by`, `item_remarks`, `item_wholesale_price`) VALUES (null,'$itemCode','$itemName','$itemRPrice','$itemStock','$itemCat','$itemSup','$dateNow','$updatedBy','Imported','$itemWPrice')";
                    mysqli_query($con, $importInvn);
                }

            }else{
                $checkExisting = "SELECT * FROM `item_no_barcode` WHERE `item_code` = '$itemCode'";
                $resultExisting = mysqli_query($con, $checkExisting);
                if(mysqli_num_rows($resultExisting) == 0){
                    $fileDest = "../images/items/default-image.png";

                    $importwob = "INSERT INTO `item_no_barcode`(`item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_stock`, `itemnb_category`, `itemnb_suppplier`, `date_updated`, `updated_by`, `itemnb_remarks`, `itemnb_img`, `itemnb_wholesale_price`) VALUES ($itemCode,'$itemName','$itemRPrice','$itemStock','$itemCat','$itemSup','".date('Y-m-d')."','$updatedBy','Imported','$fileDest','$itemWPrice')";
                    mysqli_query($con, $importwob);
                }else{
                    $delExisting = "DELETE FROM `item_no_barcode` WHERE `item_code` = '$itemCode'";
                    mysqli_query($con, $delExisting);
    
                    $importwob = "INSERT INTO `item_no_barcode`(`item_code`, `itemnb_name`, `itemnb_retail_price`, `itemnb_stock`, `itemnb_category`, `itemnb_suppplier`, `date_updated`, `updated_by`, `itemnb_remarks`, `itemnb_img`, `itemnb_wholesale_price`) VALUES ($itemCode,'$itemName','$itemRPrice','$itemStock','$itemCat','$itemSup','".date('Y-m-d')."','$updatedBy','Imported','$itemImg','$itemWPrice')";
                    mysqli_query($con, $importwob);
                }
            }
        }
        $z++;
    }

    $_SESSION['importSuccess'] = true;
    header("location: ./inventory.php");

?>