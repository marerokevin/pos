<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $recentNum = 0;
    $recentLoc = "";
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
    <title>Request</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.min.js"></script>
</head>
<body onload="navF()">

    <?php
        require_once('./nav.php');
    
        if(!isset($_SESSION['userError'])){
        }else{
            if ($_SESSION['userError'] == true){
                ?>
                    <script>
                        swal({
                            icon: "error",
                            title: "The Card Number you entered does not exist!",
                        }).then((value) => {
                            $('#userSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['userError'] = false;
            }
        }

        if(!isset($_SESSION['amountError'])){
        }else{
            if ($_SESSION['amountError'] == true){
                ?>
                    <script>
                        swal({
                            icon: "error",
                            title: "The Card Number you entered has no remaining balance!",
                        }).then((value) => {
                            $('#userSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['amountError'] = false;
            }
        }

        if(!isset($_SESSION['reqSuccess'])){
        }else{
            if ($_SESSION['reqSuccess'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Request Successful!",
                        }).then((value) => {
                            $('#userSearch').focus();
                        });
                    </script>
                <?php
                $_SESSION['reqSuccess'] = false;
            }
        }
    ?>

    <div id="admin-cat-con">
        <div class="top-con">
            <div class="title-con">
                <span>Request Money</span>
            </div>
            <div class="control-con">
                <div class="search-con">
                    <input type="text" id="userSearch" class="form-control" placeholder="Search...">
                </div>
                <div class="button-con">
                    <button class="btn btn-primary btnAdd">Request</button>
                </div>
            </div>
        </div>
        <div class="content-con">
            <div class="table-con">
                <table>
                    <thead>
                        <th>Card Number</th>
                        <th>Name</th>
                        <th>Amount</th>
                        <th>Date</th>
                        <th>Time</th>
                    </thead>
                    <tbody id="tableBody">
                        <?php
                            $queryReq = "SELECT * FROM `req_tran`";
                            $resultReq = mysqli_query($con, $queryReq);
                            if(mysqli_num_rows($resultReq) > 0){
                                while($rowReq = mysqli_fetch_assoc($resultReq)){
                                    ?>
                                        <tr>
                                            <td><?php echo $rowReq['user_card']; ?></td>
                                            <td><?php echo ucwords($rowReq['user_name']); ?></td>
                                            <td><?php echo $rowReq['req_amount']; ?></td>
                                            <td><?php echo $rowReq['req_date']; ?></td>
                                            <td><?php echo $rowReq['req_time']; ?></td>
                                        </tr>
                                    <?php
                                }
                            }else{
                                ?><tr><td>NO RECORD</td></tr><?php
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal-add-edit visually-hidden">
        <div class="inner-modal">
            <div class="top-con">
                <div class="modal-title-con">REQUEST</div>
            </div>
            <form id="formAE" action="./request-get-name.php" method="POST" class="content-con">
                <div class="row">
                    <div class="col-6">
                        <label>Card Number:</label>
                    </div>
                    <div class="col-6">
                        <input class="form-control" id="userCard" name="userCard" type="text" required autocomplete="off">
                    </div>
                </div>
                <div class="row">
                    <div class="btnCol col-6">
                        <input type="submit" value="NEXT" class="btn btn-primary">
                    </div>
                    <div class="btnCol col-6">
                        <a href="./req-cancel.php" class="btn btn-secondary btnCancel">CANCEL</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal-delete <?php if(!isset($_SESSION['curCardNum'])){ echo 'visually-hidden'; } ?>">
        <div class="inner-modal">
            <div class="top-con">
                <div class="title-con">REQUEST</div>
            </div>
            <form class="content-con" action="./request-amount.php" method="POST">
                <div class="row">
                    <div class="col-6">
                        <label>Card Number: </label>
                    </div>
                    <div class="col-6">
                        <span><?php echo $_SESSION['curCardNum']; ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Name: </label>
                    </div>
                    <div class="col-6">
                        <span><?php echo $_SESSION['req_name']; ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Available Balance: </label>
                    </div>
                    <div class="col-6">
                        <span class="curAmount"><?php echo $_SESSION['curAmount']; ?></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <label>Amount: </label>
                    </div>
                    <div class="col-6">
                        <input type="text" class="form-control" id="reqAmount" name="reqAmount"<?php if(isset($_SESSION['curCardNum'])){ echo 'autofocus'; } ?>>
                    </div>
                </div>
                <div class="row">
                    <div class="btnCol col-6">
                        <input type="submit" value="Submit" class="btn btn-danger">
                    </div>
                    <div class="btnCol col-6">
                        <a href="./req-cancel.php" class="btn btn-secondary btnCancel">CANCEL</a>
                    </div>
                </div>
            </form>
        </div>
    </div>


    <script>
        function navF(){
            $('.request').addClass('active');
            $('.request').addClass('disabled');
        }

        $(document).ready(function(){
            $(document).on('keydown', function(e){
                var eKey = e.keyCode;

                if(eKey == '27'){
                    $('.btnCancel').click();
                }
            });

            $('.btnAdd').click(function(){
                $('.modal-add-edit').removeClass('visually-hidden');
                $('#userCard').focus();
            });

            $('.btnEdit').click(function(){
                var userId = $(this).data('id');
                var username = $(this).data('username');
                var cName = $(this).data('cname');
                var userCard = $(this).data('rfid');
                var userRole = $(this).data('role');
                $('.modal-add-edit').removeClass('visually-hidden');
                $('.modal-title-con').html('EDIT USER');
                $('#userPass').attr('required', false);
                $('#userId').val(userId);
                $('#userName').val(username);
                $('#cashierName').val(cName);
                $('#userCard').val(userCard);
                $('#userRole').val(userRole);
                $('#userCard').focus();
                $('#formAE').prop('action', 'users-edit.php');
            });

            $("#userSearch").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#tableBody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            

            $("#reqAmount").on("keyup", function() {
                const avlBalance = $('.curAmount').html();
                var inputAmount = $("#reqAmount").val();

                console.log(avlBalance);
                console.log(inputAmount);

                if(parseInt(inputAmount) > parseInt(avlBalance)){
                    console.log('error');
                    $("#reqAmount").val(avlBalance);
                }
            });

        });
    </script>
    
</body>
</html>