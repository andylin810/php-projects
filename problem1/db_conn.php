<?php
    $server = "127.0.0.1";
    $username = "root";
    $password = "linshudi";
    $db_name = "testing";

    //connect to database
    //$conn = mysqli_connect($server,$username,$password,$db_name);
    $conn = mysqli_connect($server,$username,$password);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    //echo "success connection" . "<br>";


?>