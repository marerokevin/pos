<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $itemCode = $_SESSION['lastCode'];

    $checkTemp = "SELECT * FROM `temp_item` WHERE `item_code` = '$itemCode'";
    $resultTemp = mysqli_query($con, $checkTemp);
    if(mysqli_num_rows($resultTemp) > 0){
        while($rowTemp = mysqli_fetch_assoc($resultTemp)){
            if($rowTemp['temp_quantity'] == 1){
                $newQty = 1;
                $newBasePrice = $rowTemp['temp_baseprice'];
            }else{
                $newQty = $rowTemp['temp_quantity'] - 1;
                $basePrice = $rowTemp['temp_baseprice'] / $rowTemp['temp_quantity'];
                $newBasePrice = $newQty * $basePrice;
            }
            $newTotal = $newQty * $rowTemp['temp_price'];

            $updateTemp = "UPDATE `temp_item` SET `temp_quantity`='$newQty',`temp_total`='$newTotal',`temp_baseprice`='$newBasePrice' WHERE `item_code` = $itemCode";
            mysqli_query($con, $updateTemp);
        }
    }

    header("location: home.php");


?>