<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");
    
    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $getFromDate = $_POST['fromDate'];
    $getToDate = $_POST['toDate'];
    $dateFrom = date("Y-m-d H:i:s", strtotime(date($getFromDate)));
    $dateTo = date("Y-m-d H:i:s", strtotime(date($getToDate)." +1 day")-1);

    $fDate = date("F j, Y", strtotime($getFromDate));
    $tDate = date("F j, Y", strtotime($getToDate));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.3/css/bootstrap.min.css">
    <title>Sales Report</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>

    <style>
        @page { 
            size: auto;  margin: 0mm;  
        }
        body{
            padding: 50px 50px;
        }
        h2{
            font-weight: 900;
            font-size: 25px;
            padding: 0;
            margin: 0;
        }
        h3{
            font-weight: 900;
            font-size: 16px;
            padding: 0;
            margin: 10px 0;
        }
        h4{
            font-weight: 900;
            font-size: 16px;
            padding: 0;
            margin: 10px 0;
        }
        h5{
            font-weight: 900;
            font-size: 16px;
            padding: 0;
            margin: 10px 0 20px 0;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }

        td,th{
            border: 1px solid #444;
            padding: 5px 20px;
        }
        th{
            text-align: left;
            font-size: 14px;
            min-width: 150px;
        }
        td{
            font-size: 12px;
            min-width: 150px;
        }
        td:nth-child(2),td:nth-child(3),td:nth-child(4),th:nth-child(2),th:nth-child(3),th:nth-child(4){
            width: 120px;
            text-align: center;
        }
    </style>
</head>
<body onload="reportPrint()">

    <h2>SALES REPORT</h2>
    <div>
        <h3>Date: <?php echo $fDate; ?> - <?php echo $tDate; ?></h3>
        <h4><?php echo $_SESSION['branch_name']; ?></h4>
        <h5>Location: <?php echo $_SESSION['branch_loc']; ?></h5>
    </div>
    <table>
        <thead>
            <th>Date</th>
            <th>Total Sales (₱)</th>
            <th>Total Cost of Goods (₱)</th>
            <th>Total Profit (₱)</th>
        </thead>
        <tbody>
            <?php

            $grandTotal = 0;
            $totalCost = 0;
            $totalProfit = 0;

            $querySales ="SELECT SUM(`tran_total`) AS total, SUM(`tran_baseprice`) AS cost, DATE(`tran_date_time`) AS thisDate FROM (SELECT DISTINCT(`tran_num`),`tran_total`, `tran_baseprice`,`tran_date_time` FROM `transaction_logs` WHERE `tran_date_time` BETWEEN '$dateFrom' AND '$dateTo') AS SelTran GROUP BY DATE(`tran_date_time`)";
            $resultSales = mysqli_query($con, $querySales);
            if(mysqli_num_rows($resultSales) > 0){
                while($rowSales = mysqli_fetch_assoc($resultSales)){
                    $grandTotal = $grandTotal + $rowSales['total'];
                    $totalCost = $totalCost + $rowSales['cost'];
                    $profit = $rowSales['total'] - $rowSales['cost'];
                    $totalProfit = $totalProfit + $profit;
                    ?>
                        <tr>
                            <td><?php echo date("F j, Y", strtotime($rowSales['thisDate'])); ?></td>
                            <td><?php echo number_format($rowSales['total'],2); ?></td>
                            <td><?php echo number_format($rowSales['cost'],2); ?></td>
                            <td><?php echo number_format($profit,2); ?></td>
                        </tr>
                    <?php
                }
            }
        ?>

            <tr>
                <td style="font-size: 18px; font-weight: 900; text-align: right;">GRAND TOTAL</td>
                <td style="font-size: 18px; font-weight: 900;"><?php echo number_format($grandTotal,2); ?></td>
                <td style="font-size: 18px; font-weight: 900;"><?php echo number_format($totalCost,2); ?></td>
                <td style="font-size: 18px; font-weight: 900;"><?php echo number_format($totalProfit,2); ?></td>
            </tr>
        </tbody>
    </table>

    <script>
        function reportPrint(){
            window.print();
        }
    </script>
</body>
</html>


        