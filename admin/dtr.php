<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }
    
    $getDateFrom = $_GET['fromDate'];
    $getDateTo = $_GET['toDate'];
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
    <title>DTR</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.min.js"></script>
</head>
<body onload="navF()">

    <?php
        require_once('./nav.php');
    
        if(!isset($_SESSION['importSuccess'])){
        }else{
            if ($_SESSION['importSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Import Has Been Successfully Finished!",
                        }).then((value) => {
                            $('#itemSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['importSuccess'] = false;
            }
        }
    ?>

    <div id="admin-dtr-con">
        <div class="top-con">
            <div class="title-con">
                <span>Daily Time Record</span>
                <div class="cur-date-con">
                    <span><?php echo date('F j, Y'); ?></span>
                </div>
            </div>
            <div class="control-con">
                <div class="date-con">
                    <span>From: </span><span><input type="date" name="fromDate" id="fromDate" value="<?php echo $getDateFrom; ?>"></span>
                </div>
                <div class="date-con">
                    <span>To: </span><span><input type="date" name="toDate" id="toDate" value="<?php echo $getDateTo; ?>"></span>
                </div>
                <div class="date-con btn-con">
                    <input type="button" id="searchButton" value="Search" class="btn btn-primary">
                </div>
            </div>
        </div>
        <div class="content-con">
            <div class="table-con">
                <table>
                    <thead>
                        <th>NAME</th>
                        <th>TIME IN</th>
                        <th>TIME OUT</th>
                        <th>LOG DATE</th>
                    </thead>
                    <tbody>
                        <?php
                            $dateFrom = date("Y-m-d H:i:s", strtotime(date($getDateFrom)));
                            $dateTo = date("Y-m-d H:i:s", strtotime(date($getDateTo)." +1 day")-1);
                            $queryDTR = "SELECT * FROM `dtr` WHERE `log_date` BETWEEN '$dateFrom' AND '$dateTo' ORDER BY `dtr_id` DESC";
                            $resultDTR = mysqli_query($con, $queryDTR);
                            if(mysqli_num_rows($resultDTR) > 0){
                                while($rowDTR = mysqli_fetch_assoc($resultDTR)){
                                    ?>
                                        <tr>
                                            <td><?php echo $rowDTR['cashier']; ?></td>
                                            <td><?php echo $rowDTR['time_in']; ?></td>
                                            <td><?php echo $rowDTR['time_out']; ?></td>
                                            <td><?php echo $rowDTR['log_date']; ?></td>
                                        </tr>
                                    <?php
                                }
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        function navF(){
            $('.dtr').addClass('active');
            $('.dtr').addClass('disabled');
        }

        $(document).ready(function(){
            $('#searchButton').click(function(){
                var fromDate = $('#fromDate').val();
                var toDate = $('#toDate').val();
                var currUrl = new URL(window.location.href);
                var urlPar = currUrl.searchParams;
                urlPar.delete('fromDate');
                urlPar.delete('toDate');
                window.location.href = currUrl+'?fromDate='+fromDate+'&toDate='+toDate;
            });
        });
            
    </script>
    
</body>
</html>