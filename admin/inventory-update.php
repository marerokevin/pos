<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $itemCode = $_POST['addStockItemCode'];
    $itemName = $_POST['addStockItemName'];
    $itemSup = $_POST['addStockItemSup'];
    $addStock = $_POST['stockValue'];
    $drNum = $_POST['drNum'];
    $_SESSION['drNum'] = $drNum;

    $dateNow = date('Y-m-d H:i:s');
    $cName = $_SESSION['cashier_name'];
    $loc = $_SESSION['branch_loc'];
    
    $updateStock = "UPDATE `item_no_barcode` SET `itemnb_stock` = (`itemnb_stock` + $addStock) WHERE `item_code` = '$itemCode'";
    mysqli_query($con, $updateStock);

    $updateStock = "UPDATE `item_with_barcode` SET `item_stock` = (`item_stock` + $addStock) WHERE `item_code` = '$itemCode'";
    mysqli_query($con, $updateStock);


    $insertTranLogs = "INSERT INTO `transaction_logs`(`log_id`, `tran_num`, `tran_item`, `tran_qty`, `tran_total`, `tran_date_time`, `tran_cashier`, `tran_location`, `tran_type`, `sup_name`) VALUES (null,'$drNum','$itemName','$addStock','0','$dateNow','$cName','$loc','In','$itemSup')";
    mysqli_query($con, $insertTranLogs);

    $_SESSION['UpdateStockSuccess'] = true;
    header("location: inventory.php");
?>