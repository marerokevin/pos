<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
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
    <link rel="icon" href="../images/logo/shops.png">
    <title>DTR</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
</head>
<body>
    
    <div id="dtr-con">
        <div class="top-con">
            <div class="title-con">
                <div class="back-con">
                    <a class="btn btn-secondary" href="./home.php">
                        <svg viewBox="0 0 448 512">
                            <path d="M447.1 256C447.1 273.7 433.7 288 416 288H109.3l105.4 105.4c12.5 12.5 12.5 32.75 0 45.25C208.4 444.9 200.2 448 192 448s-16.38-3.125-22.62-9.375l-160-160c-12.5-12.5-12.5-32.75 0-45.25l160-160c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L109.3 224H416C433.7 224 447.1 238.3 447.1 256z"/>
                        </svg>
                        <span class="backText">BACK</span>
                    </a>
                </div>
                <span class="pageTitle">Daily Time Record</span>
                <div class="date-con">
                    <span><?php echo date('F j, Y') ?></span>
                </div>
            </div>
        </div>
        <div class="content-con">
            <div class="content-top-con">
                <form action="./dtr-in-out.php" method="POST" class="scanner-con">
                    <input type="text" class="form-control" id="inputCard" name="inputCard" autocomplete="off" autofocus>
                    <input type="submit" value="submit" class="visually-hidden">
                </form>
            </div>
            <div class="content-bottom-con">
                <div class="response-con">
                    <div class="details-con">
                        <span><?php if(isset($_SESSION['cName'])){ echo $_SESSION['cName']; }else if(isset($_SESSION['unregError'])){ echo 'Unregistered Card!';unset($_SESSION['unregError']); } ?></span>
                    </div>
                    <div class="details-con">
                        <span><?php if(isset($_SESSION['status'])){ echo $_SESSION['status']; }else if(isset($_SESSION['unregError'])){ unset($_SESSION['unregError']); } ?></span>
                    </div>
                    <div class="details-con">
                        <span><?php if(isset($_SESSION['curTime'])){ echo 'TIME: '.$_SESSION['curTime']; }else if(isset($_SESSION['unregError'])){ unset($_SESSION['unregError']); } ?></span>
                    </div>
                </div>
                <div class="table-con">
                    <table>
                        <thead>
                            <tr>
                                <th>NAME</th>
                                <th>TIME IN</th>
                                <th>TIME OUT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $curDate = date('Y-m-d');
                                $queryDTR = "SELECT * FROM `dtr` WHERE `log_date` = '$curDate' ORDER BY `dtr_id` DESC";
                                $resultDTR = mysqli_query($con, $queryDTR);
                                if(mysqli_num_rows($resultDTR) > 0){
                                    while($rowDTR = mysqli_fetch_assoc($resultDTR)){
                                        ?>
                                            <tr>
                                                <td><?php echo $rowDTR['cashier']; ?></td>
                                                <td><?php echo $rowDTR['time_in']; ?></td>
                                                <td><?php echo $rowDTR['time_out']; ?></td>
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
    </div>

    <script>
        $(document).ready(function(){

        });
    </script>
    
</body>
</html>