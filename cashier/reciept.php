<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $getDisc = "SELECT * FROM `admin_settings` WHERE `set_id` = 1";
    $resultDisc = mysqli_query($con, $getDisc);
    $rowDisc = mysqli_fetch_assoc($resultDisc);
    $wholesaleDisc = ($rowDisc['discount'] / 100);

    $lastInvNo;
    $recieptCode = $_SESSION['branch_code'];

    $getLastTran = "SELECT * FROM `transaction_logs` ORDER BY `log_id` DESC LIMIT 1";
    $resultLastTran = mysqli_query($con, $getLastTran);
    if(mysqli_num_rows($resultLastTran) > 0){
        $rowLastTran = mysqli_fetch_assoc($resultLastTran);
        $lastInvNo = $rowLastTran['log_id'] + 1;
    }else{
        $lastInvNo = 1;
    }

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
                <span>Invoice No:</span><span class="invoiceNumber"></span>
            </div>
            <div class="top-mid-con">
                <span>D/T: </span><span id="dateTimeNow"></span>
            </div>
            <div class="top-bot-con">
                <span>Cashier: </span><span class="cashierName"><?php echo $_SESSION['cashier_name']; ?></span>
            </div>
        </div>

        <div class="table-con">
            <table>
                <thead>
                    <th>Item</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    <?php
                        $queryTempItems = "SELECT * FROM `temp_item` ORDER BY temp_id DESC";
                        $resultTempItems = mysqli_query($con, $queryTempItems);
                        if(mysqli_num_rows($resultTempItems) > 0){
                            $c = 0;
                            while($rowTempItems = mysqli_fetch_assoc($resultTempItems)){
                                $c++;
                                ?>
                                    <tr>
                                        <td style="text-align: left;" colspan="4"><?php echo $c." ".$rowTempItems['temp_name']; ?></td>
                                        
						</tr>
						<tr>
							<td></td>
                                        <td><?php echo number_format($rowTempItems['temp_price'],2); ?></td>
				                        <td><?php echo $rowTempItems['temp_quantity']; ?></td>
                                        <td><?php echo number_format($rowTempItems['temp_total'],2); ?></td>
                                    </tr>
                                <?php
                            }
                            $gTotal = ($_SESSION['subTotal'] - ($_SESSION['subTotal']*$wholesaleDisc));
                            $_SESSION['gTotal'] = $gTotal;
                        }
                    ?>
                </tbody>
            </table>
        </div>
        <hr width="90%">
        <div class="bot-con">
            <div class="bot-bot-con"><span style="float: left; margin-left: 10px;">Total Items: <?php echo $_SESSION['totalItems']; ?></span><span style="float: right; margin-right: 10px;">Subtotal: ₱ <?php echo number_format($_SESSION['subTotal'],2); ?></span></div>
            <div class="subTotal" style="margin-bottom: 10px;"><span style="float: left; margin-left: 10px;">Total Quantity: <?php echo $_SESSION['totalQty']; ?></span><span style="text-align: right; margin-left: 25px;">Discount: <?php echo ($wholesaleDisc*100); ?>%</span></div>
        </div>
        <hr width="90%">
        <div class="grand-total">
            <p style="font-size: 12px; text-align: right; margin-right: 10px; margin-top: 5px;">Grand Total: ₱ <?php echo number_format($gTotal,2); ?></p>
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
            $('.invoiceNumber').html(invNo);


            var date = year+'-'+nmonth+'-'+nday;
            var time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
            var dateTime = date+' '+time;
            $('#dateTimeNow').html(dateTime);

            window.print();

            swal({
                title: "Printing Reciept...",
            }).then((value) => {
                window.location.href = './del-temp-after-transaction.php?invNo='+invNo;
            });
        }
    </script>
</body>
</html>