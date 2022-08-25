<?php
    session_start();
    date_default_timezone_set("Asia/Manila");
    include("../db/conn.php");

    if(!isset($_SESSION['conected'])){
        header('location: ../login.php');
    }

    $newNote = $_POST['inputNote'];
    $enewNote = mysqli_real_escape_string($con, $newNote);

    $updateNote = "UPDATE `admin_settings` SET `note`='$enewNote' WHERE set_id='1'";
    mysqli_query($con, $updateNote);

    $_SESSION['successNote'] = true;
    // $_SESSION['note'] = $enewNote;
    header('location: settings.php');
?>