<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $querySetting = "SELECT * FROM `admin_settings`";
    $resultSetting = mysqli_query($con, $querySetting);
    $rowSetting = mysqli_fetch_assoc($resultSetting);
    $name = $rowSetting['branch_name'];
    $location = $rowSetting['branch_location'];
    $code = $rowSetting['reciept_code'];
    $msg = $rowSetting['reciept_msg'];
    $safeStock = $rowSetting['safe_stock'];
    $sysTheme = $rowSetting['theme'];
    $note = $rowSetting['note'];

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
    <title>Settings</title>

    <script src="../js/jquery-3.6.0.min.js"></script>
    <script src="../bootstrap-5.1.3/js/bootstrap.bundle.min.js"></script>
    <script src="../js/sweetalert.min.js"></script>
    <script src="../js/chart.min.js"></script>
</head>
<body onload="navF()">

    <?php
        require_once('./nav.php');

        if(!isset($_SESSION['successName'])){
        }else{
            if ($_SESSION['successName'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Store Name has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successName'] = false;
            }
        }

        if(!isset($_SESSION['successLoc'])){
        }else{
            if ($_SESSION['successLoc'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Store Location has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successLoc'] = false;
            }
        }

        if(!isset($_SESSION['successCode'])){
        }else{
            if ($_SESSION['successCode'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Store Code has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successCode'] = false;
            }
        }

        if(!isset($_SESSION['successLogo'])){
        }else{
            if ($_SESSION['successLogo'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Store Logo has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successLogo'] = false;
            }
        }

        if(!isset($_SESSION['successMsg'])){
        }else{
            if ($_SESSION['successMsg'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Message on reciept has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successMsg'] = false;
            }
        }

        if(!isset($_SESSION['successSafe'])){
        }else{
            if ($_SESSION['successSafe'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Safe Stock has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successSafe'] = false;
            }
        }

        if(!isset($_SESSION['successTheme'])){
        }else{
            if ($_SESSION['successTheme'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Theme has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successTheme'] = false;
            }
        }

        if(!isset($_SESSION['successNote'])){
        }else{
            if ($_SESSION['successNote'] == true){
                ?>
                    <script>
                        swal({
                            icon: "success",
                            title: "Note/Reminder has been updated successfully!",
                        })
                    </script>
                <?php
                $_SESSION['successNote'] = false;
            }
        }
    ?>

    <div id="admin-settings-con">
        <div class="top-con">
            <div class="title-con">
                <span>STORE SETTINGS</span>
            </div>
        </div>
        <div class="settings-con">
            <div class="left-con">
                <div class="logo-con">
                    <form action="./setting-logo.php" method="POST" enctype="multipart/form-data">
                        <p>Logo</p>
                        <img src="../images/logo/<?php echo $_SESSION['logo']; ?>" alt="">
                        <span><input type="file" class="form-control" id="inputLogo" name="inputLogo" autocomplete="off" accept="image/*"><input type="submit" id="submitLogo" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
                <div class="set-con">
                    <form action="./setting-name.php" method="POST">
                        <p>Name</p>
                        <span><input type="text" class="form-control" id="inputName" name="inputName" value="<?php echo $name; ?>" autocomplete="off"><input type="submit" id="submitName" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
                <div class="set-con">
                    <form action="./setting-location.php" method="POST">
                        <p>Location</p>
                        <span><input type="text" class="form-control" id="inputLocation" name="inputLocation" value="<?php echo $location; ?>" autocomplete="off"><input type="submit" id="submitLocation" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
                <div class="set-con">
                    <form action="./setting-code.php" method="POST">
                        <p>Code</p>
                        <span><input type="text" class="form-control" id="inputCode" name="inputCode" value="<?php echo $code; ?>" autocomplete="off"><input type="submit" id="submitCode" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
                <div class="set-con">
                    <form action="./setting-safestock.php" method="POST">
                        <p>Safe Stock</p>
                        <span><input type="text" class="form-control" id="inputSafe" name="inputSafe" value="<?php echo $safeStock; ?>" autocomplete="off"><input type="submit" id="submitSafe" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
            </div>

            <div class="right-con">
                <div class="theme-con">
                    <form action="./setting-theme.php" method="POST">
                        <p>Theme</p>
                        <span>
                            <input class="form-check-input" type="radio" name="systemTheme" id="themeBlue" value="blue" <?php if($_SESSION['sysTheme'] == 'blue'){ echo 'checked'; } ?>>
                            <label class="form-check-label" for="themeBlue">BLUE</label>

                            <input class="form-check-input" type="radio" name="systemTheme" id="themeRed" value="red" <?php if($_SESSION['sysTheme'] == 'red'){ echo 'checked'; } ?>>
                            <label class="form-check-label" for="themeRed">RED</label>

                            <input class="form-check-input" type="radio" name="systemTheme" id="themeGreen" value="green" <?php if($_SESSION['sysTheme'] == 'green'){ echo 'checked'; } ?>>
                            <label class="form-check-label" for="themeGreen">GREEN</label>
                        </span>
                        <input type="submit" id="submitTheme" class="btn btn-primary" value="SAVE">
                    </form>
                </div>
                <div class="msg-con">
                    <form action="./setting-msg.php" method="POST">
                        <p>Reciept Message</p>
                        <span><input type="text"maxlength="200" class="form-control" id="inputMsg" name="inputMsg" value="<?php echo $msg; ?>" autocomplete="off"><input type="submit" id="submitMsg" class="btn btn-primary" value="SAVE" disabled></span>
                    </form>
                </div>
                <div class="note-con">
                    <form action="./setting-note.php" method="POST">
                        <p>Note / Reminder</p>
                        <span>
                            <textarea name="inputNote" id="inputNote" cols="70" rows="5"><?php echo $note; ?></textarea>
                            <input type="submit" id="submitNote" class="btn btn-primary" value="SAVE" disabled>
                        </span>
                    </form>
                </div>
                <div class="backup-con">
                    <p>Backup Database</p>
                    <div class="button-con">
                        <button class="btn btn-primary" id="btnBackup">INITIATE BACKUP</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function navF(){
            $('.setting').addClass('active');
            $('.setting').addClass('disabled');
        }

        $(document).ready(function(){
            const sName = <?php echo json_encode($name); ?>;
            const sLocation = <?php echo json_encode($location); ?>;
            const sCode = <?php echo json_encode($code); ?>;
            const sMsg = <?php echo json_encode($msg); ?>;
            const sSafe = <?php echo json_encode($safeStock); ?>;
            const sNote = <?php echo json_encode($note); ?>;

            $('#inputLogo').change(function(){
                $('#submitLogo').attr('disabled', false);
            });
            $('#inputName').on('keyup',function(){
                if($(this).val() == sName){
                    $('#submitName').attr('disabled', true);
                }else{
                    $('#submitName').attr('disabled', false);
                }
            });
            $('#inputLocation').on('keyup',function(){
                if($(this).val() == sLocation){
                    $('#submitLocation').attr('disabled', true);
                }else{
                    $('#submitLocation').attr('disabled', false);
                }
            });
            $('#inputCode').on('keyup',function(){
                if($(this).val() == sCode){
                    $('#submitCode').attr('disabled', true);
                }else{
                    $('#submitCode').attr('disabled', false);
                }
            });
            $('#inputSafe').on('keyup',function(){
                if($(this).val() == sSafe){
                    $('#submitSafe').attr('disabled', true);
                }else{
                    $('#submitSafe').attr('disabled', false);
                }
            });
            $('#inputMsg').on('keyup',function(){
                if($(this).val() == sMsg){
                    $('#submitMsg').attr('disabled', true);
                }else{
                    $('#submitMsg').attr('disabled', false);
                }
            });
            $('#inputNote').on('keyup',function(){
                if($(this).val() == sNote){
                    $('#submitNote').attr('disabled', true);
                }else{
                    $('#submitNote').attr('disabled', false);
                }
            });


            $('#restoreFile').change(function(){
                $('#submitRestore').attr('disabled', false);
            });
            $('#btnBackup').click(function(){
                window.location.href = "./backup-database.php";
            });

            // $(document).on('keydown', function(e){
            //     if(e.keyCode == 13){
            //         e.preventDefault();
            //     }
            // });
        });
            
    </script>
    
</body>
</html>