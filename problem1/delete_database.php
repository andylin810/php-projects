<?php
    if(isset($_POST["database"])) {
        require 'db_conn.php';
        $dbName = $_POST["database"];
        $sql = "DROP DATABASE $dbName;";
        $result = mysqli_query($conn, $sql);
        if(!$result) {
            echo "error";
        } else {
            echo $_POST["database"];
        }
    }
    