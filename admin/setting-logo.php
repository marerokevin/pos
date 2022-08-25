<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $file = $_FILES['inputLogo'];

    $fileName = $_FILES['inputLogo']['name'];
    $fileTmpName = $_FILES['inputLogo']['tmp_name'];
    $fileType = $_FILES['inputLogo']['type'];

    $fileTempExt = explode('.', $fileName);
    $fileExt = strtolower(end($fileTempExt));

    $newFileName = "store-logo.".$fileExt;
    $fileDest = "../images/logo/".$newFileName;
    
    move_uploaded_file($fileTmpName, $fileDest);

    $updateLogo = "UPDATE `admin_settings` SET `branch_logo`='$newFileName' WHERE set_id='1'";
    mysqli_query($con, $updateLogo);

    $_SESSION['logo'] = $newFileName;
    $_SESSION['successLogo'] = true;
    header('location: settings.php');
?>