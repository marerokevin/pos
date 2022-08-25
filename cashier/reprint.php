<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $invoice = $_GET['invoice'];
    $c = 0;
    $q = 0;

    $getTran = "SELECT * FROM `transaction_logs` WHERE `tran_num` = '$invoice'";
    $resultTran = mysqli_query($con, $getTran);
    $rowTran = mysqli_fetch_assoc($resultTran);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../styles/print.css" media="print">
    <title>Reciept</title>
    <script src="../js/jquery-3.6.0.min.js"></script>
        <script src="../js/sweetalert.min.js"></script>
</head>
<body onload="prinThis()">
    <div class="rec-con">
        <div class="title-con">
            <h6><?php echo $_SESSION['branch_name']; ?></h6>
            <p><?php echo $_SESSION['branch_loc']; ?></p>
        </div>
        <div class="top-con">
            <div class="top-top-con">
                <span>Invoice No:</span><span class="invoiceNumber"><?php echo $invoice; ?></span>
            </div>
            <div class="top-mid-con">
                <span>D/T: </span><span id="dateTimeNow"><?php echo $rowTran['tran_date_time']; ?></span>
            </div>
            <div class="top-bot-con">
                <span>Cashier: </span><span class="cashierName"><?php echo $rowTran['tran_cashier']; ?></span>
            </div>
        </div>

        <div class="table-con">
            <table>
                <thead>
                    <th>Item</th>
                    <!-- <th>Price</th> -->
                    <th>Qty</th>
                    <!-- <th>Total</th> -->
                </thead>
                <tbody>
                    <?php

                        $getTran2 = "SELECT * FROM `transaction_logs` WHERE `tran_num` = '$invoice'";
                        $resultTran2 = mysqli_query($con, $getTran2);
                        while($rowTran2 = mysqli_fetch_assoc($resultTran2)){
                            $c++;
                            ?>
                                <tr>
                                    <td style="text-align: left;"><?php echo $c." ".$rowTran2['tran_item']; ?></td>
                                    <!-- <td><?php //echo number_format($rowTran2['temp_price'],2); ?></td> -->
                                    <td><?php echo $rowTran2['tran_qty']; ?></td>
                                    <!-- <td><?php //echo number_format($rowTran2['temp_total'],2); ?></td> -->
                                </tr>
                            <?php
                            $q = $q + $rowTran2['tran_qty'];
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <hr width="90%">
        <div class="bot-con">
            <div style="width: 100%;" class="bot-bot-con"><span style="float: left; margin-left: 10px;">Total Items: <?php echo $c; ?></span><span style="float: right; margin-right: 10px;">Subtotal: ₱ <?php echo number_format($rowTran['tran_total'],2); ?></span></div>
            <div class="subTotal" style="margin-bottom: 10px; width: 100%;"><span style="float: left; margin-left: 10px;">Total Quantity: <?php echo $q; ?></span><span style="text-align: right; margin-left: 25px;">Discount: 0%</span></div>
        </div>
        <hr width="90%">
        <div class="grand-total">
            <p style="font-size: 12px; text-align: right; margin-right: 10px; margin-top: 5px;">Grand Total: ₱ <?php echo number_format($rowTran['tran_total'],2); ?></p>
        </div>
        <hr width="90%">
        <div class="footer">
            <div style="font-size: 12px; margin-bottom: 100px"><?php echo $_SESSION['msg']; ?></div>
        </div>
    </div>

    <script>
        function prinThis(){

            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth()+1;
            if(month < 10){
                var nmonth = "0" + month;
            }else{
                var nmonth = month;
            }
            var day = today.getDate();
            if(day < 10){
                var nday = "0" + day;
            }else{
                var nday = day;
            }

            var invYear = year.toString().slice(-2);
            var invNo = <?php echo json_encode($recieptCode); ?> + '-' + invYear + nmonth + nday + <?php echo json_encode($lastInvNo); ?>;
            // $('.invoiceNumber').html(invNo);


            var date = year+'-'+nmonth+'-'+nday;
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var dateTime = date+' '+time;
            // $('#dateTimeNow').html(dateTime);




            window.print();
            swal({
                title: "Printing Reciept...",
            }).then((value) => {
                window.location.href = './transaction-history.php?dateFrom=<?php echo date('Y-m-d'); ?>&dateTo=<?php echo date("Y-m-d"); ?>&searchDate="Search"';
            });
        }
    </script>
</body>
</html>