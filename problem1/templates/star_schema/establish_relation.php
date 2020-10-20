<?php

    /**
     * This file displays a form which contains a select, promping user to select a table in the 
     * database as a fact table.
     */

    require '../../db_conn.php';

    session_start();
    if(isset($_POST['button_name'])){
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);
        $sql = "show tables;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){

            echo "Please select the fact table to establish relationship:";
            echo "<form id='make-fact-table' action='templates/star_schema/fact_table.php' method='post'>";

            echo "<select name='fact-table'>";
            while($row = mysqli_fetch_array($result)){
                $name = $row[0];
                if($name != "fact_table" && $name != 'dim_table') {
                    echo "<option value='$name'>$name</option>";
                }
                
            }
            echo  "</select>";
            // mysqli_data_seek($result,0);
            
            echo "<input name='submit' type='submit' value='select as fact table'>";
            echo "</form>";
        }

    } else {
        echo "something wrong";
    }
        


?>