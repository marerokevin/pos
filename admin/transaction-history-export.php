<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $dateFrom = $_GET['dateFrom'];
    $dateGetTo = $_GET['dateTo'];
    $dateTo = date('Y-m-d', strtotime("+1 day", strtotime($dateGetTo)));

    $allTran = "SELECT * FROM `transaction_logs` WHERE `tran_date_time` BETWEEN '$dateFrom' AND '$dateTo'";
    $resultTran = mysqli_query($con, $allTran);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exporting...</title>
    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../js/table2csv.min.js"></script>
</head>
<body onload="tranExp()">
    <div style="width: 100vw; height: 100vh">
        <h3>Please Wait...</h3><br>
        <h3>Exporting Transaction History...</h3>
    </div>

    <table id="tranTable">
        <thead>
            <tr>
                <th>tran_num</th>
                <th>tran_item</th>
                <th>tran_qty</th>
                <th>tran_total</th>
                <th>tran_date_time</th>
                <th>tran_cashier</th>
                <th>tran_location</th>
                <th>tran_type</th>
            </tr>
        </thead>
        <tbody>
            <?php
                while($rowTran = mysqli_fetch_assoc($resultTran)){
                    ?>
                        <tr>
                            <td><?php echo $rowTran['tran_num']; ?></td>
                            <td><?php echo $rowTran['tran_item']; ?></td>
                            <td><?php echo $rowTran['tran_qty']; ?></td>
                            <td><?php echo $rowTran['tran_total']; ?></td>
                            <td><?php echo $rowTran['tran_date_time']; ?></td>
                            <td><?php echo $rowTran['tran_cashier']; ?></td>
                            <td><?php echo $rowTran['tran_location']; ?></td>
                            <td><?php echo $rowTran['tran_type']; ?></td>
                        </tr>
                    <?php
                }
            ?>
        </tbody>
    </table>

    <script>
            var option = {
                "filename":"transaction-history.csv"
            }

        function tranExp(){
            console.log('text');
            $("#tranTable").first().table2csv(option);
        }
    </script>
</body>
</html>