<?php
    session_start();
    
    if(isset($_POST['submit'])) {
        echo "post submitted";
        echo $_FILES['fast-upload-file']['name'];
    }
    
    if(isset($_FILES['upload-file'])){
        require 'db_conn.php';

        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);

            $fp = $_FILES['upload-file']['tmp_name'];
            $fileName = $_FILES['upload-file']['name'];
            $mark = '"';

            $sql = "LOAD DATA INFILE '$fp' 
            INTO TABLE testtb1 
            FIELDS TERMINATED BY ',' 
            ENCLOSED BY '$mark' 
            LINES TERMINATED BY '\n' 
            IGNORE 1 ROWS;";

            $result = mysqli_query($conn,$sql);
            if(!$result) {
                echo "error" . mysqli_error($conn);
            } else {
                echo "success";
            }
        } else {
            echo "select a database";
        }

    } else {
        echo "not set ";
    }

