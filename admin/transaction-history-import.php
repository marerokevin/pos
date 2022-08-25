<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $importFile = $_FILES['importTran']['tmp_name'];
    $file = fopen($importFile, "r");

    $z = 0;

    while(($column = fgetcsv($file, 10000, ",")) !== FALSE){

        $tranNum = $column[0];
        $tranItem = $column[1];
        $tranQty = $column[2];
        $tranTotal = $column[3];
        $tranDateTime = $column[4];
        $tranCashier = $column[5];
        $tranLoc = $column[6];
        $tranType = $column[7];

        if($z != 0){
            $checkExisting = "SELECT * FROM `transaction_logs` WHERE `tran_num` = '$tranNum' AND `tran_item` = '$tranItem' AND `tran_qty` = '$tranQty' AND `tran_total` = '$tranTotal' AND `tran_date_time` = '$tranDateTime' AND `tran_cashier` = '$tranCashier' AND `tran_location` = '$tranLoc' AND `tran_type` = '$tranType'";
            $resultExisting = mysqli_query($con, $checkExisting);
            if(mysqli_num_rows($resultExisting) == 0){

                $importTran = "INSERT INTO `transaction_logs`(`log_id`, `tran_num`, `tran_item`, `tran_qty`, `tran_total`, `tran_date_time`, `tran_cashier`, `tran_location`, `tran_type`) VALUES (null,'$tranNum','$tranItem','$tranQty','$tranTotal','$tranDateTime','$tranCashier','$tranLoc','$tranType')";
                mysqli_query($con, $importTran);

            }
        }
        $z++;

    }

    $_SESSION['importSuccess'] = true;
    header("location: transaction-history.php?dateFrom=".date('Y-m-d')."&dateTo=".date("Y-m-d")."&typeFilter=Out&locFilter=All&searchDate");

?>