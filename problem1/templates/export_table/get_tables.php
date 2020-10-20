<?php

    /**
     * This file gets all fields of the table being passed in by POST request
     * and return in html options to javascript.
     */
    require '../../db_conn.php';
    require '../../sql_functions.php';

    session_start();

    if(isset($_SESSION['database'])){

        if(isset($_POST['table_name'])) {
            $table = $_POST['table_name'];
            
            $db = $_SESSION['database'];
            mysqli_select_db($conn, $db);

            $fields = getAllFields($table,$conn);
            foreach($fields as $field){
                echo "<option value='$field'>$field</option>";
            }


        } else {
            echo "error";
        }
    } else {
        echo "not set";
    }