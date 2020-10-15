<?php
    require 'db_conn.php';
    if(isset($_POST['table'])) {

        session_start();
        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);
            $table = $_POST['table'];
            $sql = "DROP TABLE $table;";
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                echo "error";
            } else {
                echo $table;
            }
        } else {
            echo "db not set";
        }
        
    } else {
        echo "table name not sent ";
    }