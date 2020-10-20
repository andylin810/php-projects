<?php
    /**
     * This file returns all fields of a table that is a unique key or primary key
     * then displays them as options, to be displayed in the field select list whenever
     * a table is changed in the table select list.
     */

    require '../../db_conn.php';
    require '../../sql_functions.php';

    session_start();

    if(isset($_SESSION['database'])){

        if(isset($_POST['table_name'])) {
            $table = $_POST['table_name'];
            
            $db = $_SESSION['database'];
            mysqli_select_db($conn, $db);

            $fields = getUniqueFields($table,$conn);
            foreach($fields as $field){
                echo "<option value='$field'>$field</option>";
            }


        } else {
            echo "error";
        }
    } else {
        echo "not set";
    }