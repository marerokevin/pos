<?php

    $dbhost = "localhost";
    $dbuser = "pos";
    $dbpass = "CmDsj@9n62UR";
    $dbname = "pos";

    if(!$con = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname)){
        die("Failed to Connect to Database!");
    }

?>