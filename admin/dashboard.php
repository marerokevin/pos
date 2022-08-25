<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $todaySales = 0;
    $yesSales = 0;
    $weekSales = 0;
    $mSales = 0;
    $dailySales = 0;
    $arrayDaily = array();
    $numDays = date('t');

    // FOR WEEKLY SALES
    $ws = date("Y-m-d H:i:s", strtotime("sunday -1 week"));
    $we = date("Y-m-d H:i:s", strtotime("sunday 0 week")-1);

    $queryWeekSales = "SELECT COUNT(`tran_num`), SUM(`tran_total`) FROM (SELECT `tran_num`, `tran_total` FROM `transaction_logs` WHERE `tran_type` = 'Out' AND `tran_date_time` BETWEEN '$ws' AND '$we' GROUP BY `tran_num`) AS WSUM";
    $resultWeekSales = mysqli_query($con, $queryWeekSales);
    if(mysqli_num_rows($resultWeekSales) > 0){ 
        while($rowWeekSales = mysqli_fetch_assoc($resultWeekSales)){
            $weekSales = $rowWeekSales['SUM(`tran_total`)'];
        }
    }

    // FOR YESTERDAY SALES
    $ys = date("Y-m-d H:i:s", strtotime("yesterday"));
    $ye = date("Y-m-d H:i:s", strtotime("today")-1);

    $queryYesSales = "SELECT COUNT(`tran_num`), SUM(`tran_total`) FROM (SELECT `tran_num`, `tran_total` FROM `transaction_logs` WHERE `tran_type` = 'Out' AND `tran_date_time` BETWEEN '$ys' AND '$ye' GROUP BY `tran_num`) AS YSUM";
    $resultYesSales = mysqli_query($con, $queryYesSales);
    if(mysqli_num_rows($resultYesSales) > 0){
        while($rowYesSales = mysqli_fetch_assoc($resultYesSales)){
            $yesSales = $rowYesSales['SUM(`tran_total`)'];
        }
    }

    // FOR TODAY SALES
    $ts = date("Y-m-d H:i:s", strtotime(date('Y-m-d')));
    $te = date("Y-m-d H:i:s", strtotime(date("Y-m-d")." +1 day")-1);

    $queryTodaySales = "SELECT COUNT(`tran_num`), SUM(`tran_total`) FROM (SELECT `tran_num`, `tran_total` FROM `transaction_logs` WHERE `tran_type` = 'Out' AND `tran_date_time` BETWEEN '$ts' AND '$te' GROUP BY `tran_num`) AS YSUM";
    $resultTodaySales = mysqli_query($con, $queryTodaySales);
    if(mysqli_num_rows($resultTodaySales) > 0){
        while($rowTodaySales = mysqli_fetch_assoc($resultTodaySales)){
            $todaySales = $rowTodaySales['SUM(`tran_total`)'];
        }
    }

    // FOR DAILY SALES ON CHART AND FOR MONTH SALES
    for($i = 1; $i<=$numDays; $i++){
        $fromDate = date("Y-m-d H:i:s", strtotime(date('Y-m-'.$i)));
        $toDate = date("Y-m-d H:i:s", strtotime(date('Y-m-'.$i)." +1 day")-1);
        $dailySales = 0;
        $querySales = "SELECT `tran_num`, `tran_total` FROM `transaction_logs` WHERE `tran_type` = 'Out' AND `tran_date_time` BETWEEN '$fromDate' AND '$toDate' GROUP BY `tran_num`";
        $resultSales = mysqli_query($con, $querySales);
        if(mysqli_num_rows($resultSales) > 0){
            while($rowSales = mysqli_fetch_assoc($resultSales)){

                $dailySales = $dailySales + $rowSales['tran_total'];
                $mSales = $mSales + $rowSales['tran_total'];
            }
        }
        array_push($arrayDaily, $dailySales);
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../bootstrap-5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="../images/logo/<?php echo $_SESSION['logo']; ?>">
    <title>Dashboard</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.min.js"></script>
</head>
<body onload="navF()">

    <?php require_once('./nav.php'); ?>

    <div id="admin-dashboard-con">
        <div class="title-con">
            <span>Dashboard</span>
            <div class="date-con">
                <span><?php echo date('F j, Y'); ?></span>
            </div>
        </div>
        <div class="overview-con">
            <div class="sales-con">
                <div class="each-con todaySales">
                    <h1 class="">Today's Sales</h1>
                    <p><span>₱ </span><?php echo number_format($todaySales,2); ?></p>
                </div>
                <div class="each-con yesterdaySales">
                    <h1>Yesterday's Sales</h1>
                    <p><span>₱ </span><?php echo number_format($yesSales,2); ?></p>
                </div>
                <div class="each-con weeklySales">
                    <h1>Weekly Sales</h1>
                    <p><span>₱ </span><?php echo number_format($weekSales,2); ?></p>
                </div>
                <div class="each-con monthlySales">
                    <h1>Monthly Sales</h1>
                    <p><span>₱ </span><?php echo number_format($mSales,2); ?></p>
                </div>
            </div>
        </div>
        <div class="chart-con">
            <canvas id="wholeMonth"></canvas>
        </div>
    </div>

    <script>

        function navF(){
            $('.dashboard').addClass('active');
            $('.dashboard').addClass('disabled');
        }

        var d = new Date();
        var y = d.getFullYear();
        var m = d.getMonth()+1;
        var month;
        var numDay = new Date(y, m, 0).getDate();

        switch (m){
            case 1:
                month = "January";
                break;

            case 2:
                month = "February";
                break;
            
            case 3:
                month = "March";
                break;
            
            case 4:
                month = "April";
                break;
            
            case 5:
                month = "May";
                break;
            
            case 6:
                month = "June";
                break;
            
            case 7:
                month = "July";
                break;
            
            case 8:
                month = "August";
                break;
            
            case 9:
                month = "September";
                break;
            
            case 10:
                month = "October";
                break;
            
            case 11:
                month = "November";
                break;
            
            case 12:
                month = "December";
                break;

            default: "error";
        }

        var dateArray = [];
        var sampleData = [];

        for(var i=1; i<=numDay; i++){
            dateArray.push(i);
            sampleData.push(i);
        }

        let wChart = document.getElementById('wholeMonth').getContext('2d');
        let weekChart = new Chart(wChart, {
            type:'line',
            data:{
                labels: dateArray,
                datasets:[{
                    label:'Sales',
                    data: <?php echo json_encode($arrayDaily); ?>,
                    borderColor: '#3bafda',
                    borderWidth: 4,
                    fill: false,
                    tension: 0.4
                }]
            },

            options: {
                responsive: true,
                plugins: { 
                    title: {
                        display: true,
                        text: 'Daily Sales',
                    },
                    legend:{
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: month
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Employees'
                        },
                        min: 0,
                    }
                }
            },
        });
    </script>
    
</body>
</html>