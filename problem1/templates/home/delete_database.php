<?php

    /**
     * This script delete the selected database.
     */ 
    if(isset($_POST["database"])) {

        // connect to database
        require '../../db_conn.php';
        $dbName = $_POST["database"];

        // execute the delete database query
        $sql = "DROP DATABASE $dbName;";
        $result = mysqli_query($conn, $sql);
        if(!$result) {
            echo "error";
        } else {
            echo $_POST["database"];
        }
    }
    