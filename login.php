<?php
    session_start();
    include("./db/conn.php");

    $deleteTempItem = "TRUNCATE `temp_item`";
    mysqli_query($con, $deleteTempItem);

    $querySettings = "SELECT * FROM `admin_settings`";
    $resultSettings = mysqli_query($con, $querySettings);
    $rowSettings = mysqli_fetch_assoc($resultSettings);

    $_SESSION['logo'] = $rowSettings['branch_logo'];
    $_SESSION['branch_name'] = $rowSettings['branch_name'];
    $_SESSION['branch_loc'] = $rowSettings['branch_location'];
    $_SESSION['branch_code'] = $rowSettings['reciept_code'];
    $_SESSION['msg'] = $rowSettings['reciept_msg'];
    $_SESSION['safe_stock'] = $rowSettings['safe_stock'];
    $_SESSION['sysTheme'] = $rowSettings['theme'];
    $note = $rowSettings['note'];
    $sysTheme = $rowSettings['theme'];
    $setDate = $rowSettings['cur_date'];
    $curDate = date('Y-m-d');

    if($setDate != $curDate){
        $updateAmount = "UPDATE `users` SET `avail_amount`= '1000'";
        mysqli_query($con, $updateAmount);

        ?>
            <script>
                window.open('./admin/backup-database.php');
            </script>
        <?php

        $updateDate = "UPDATE `admin_settings` SET `cur_date`= '$curDate'";
        mysqli_query($con, $updateDate);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./bootstrap-5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/styles.css?v=<?php echo time(); ?>">
    <link rel="icon" href="./images/logo/<?php echo $_SESSION['logo']; ?>">
    <title><?php echo $_SESSION['branch_name']; ?> | Login</title>

    <script src="./js/jquery-3.6.0.min.js"></script>
    <script src="./bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
</head>
<body id="login-body">

    <?php
        if(!isset($_SESSION['errMes'])){
        }else{
            if ($_SESSION['errMes'] == 'true'){
                ?>
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Incorrect Username or Password.',
                        });
                    </script>
                <?php
                session_destroy();
                session_unset();
            }
        }
    ?>

    <div class="login-container">
        <div class="logo-name-con">
            <div class="pic-con">
                <img src="./images/logo/<?php echo $_SESSION['logo']; ?>" alt="">
            </div>
            <!-- <div class="name-con">
                <p><?php echo strtoupper($_SESSION['branch_name']); ?></p>
            </div> -->
        </div>
        <div class="login-inner-container">
            <div class="w-100 text-center mt-4 mb-3">
                <span class="fs-1 fw-bolder">SIGN IN</span>
            </div>
            <div class="w-100 px-5 mb-5">
                <form action="./login-check.php" method="POST">
                    <div class="mb-4">
                        <label for="InputUsername" class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" id="InputUsername" required autofocus autocomplete="off">
                    </div>
                    <div class="mb-4">
                        <label for="InputPassword" class="form-label">Password</label>
                        <input type="password" name="userpass" class="form-control" id="InputPassword" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="note-con">
            <div class="note"><?php echo $note; ?></div>
        </div>
    </div>

    <script>

    const sTheme = <?php echo json_encode($sysTheme); ?>;

    var r = document.querySelector(':root');

    if(sTheme == 'blue'){
        r.style.setProperty('--g1', '#56ccf2');
        r.style.setProperty('--g2', '#2f80ed');
    }else if(sTheme == 'red'){
        r.style.setProperty('--g1', '#ea384d');
        r.style.setProperty('--g2', '#d31027');
    }else if(sTheme == 'green'){
        r.style.setProperty('--g1', '#11998e');
        r.style.setProperty('--g2', '#38ef7d');
    }

    </script>
    
</body>
</html>