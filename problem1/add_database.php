<?php
    require 'db_conn.php';
    if(isset($_POST['dbName'])) {
        $dbName = $_POST['dbName'];
        $sql = "CREATE DATABASE $dbName;";
        $result = mysqli_query($conn, $sql);
        if (!$result) {
            echo "error";
        } else {
            echo $dbName;
        }
        
    }