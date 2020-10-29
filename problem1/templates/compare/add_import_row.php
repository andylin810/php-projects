<?php

    /**
     * This file adds a row to the export page allow user to enter
     * information for the new export column including column name,
     * table name, and field name.
     */

    session_start();

    require "../..//db_conn.php";
    require '../../sql_functions.php';

    if(isset($_SESSION['database'])){

        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        
        if(isset($_SESSION['import-row'])){
            $rowIndex = $_SESSION['import-row'];
            $fileColumns = $_POST['column'];
            $rowNumber = $rowIndex + 1;

            echo "<tr id='$rowIndex'>";
            echo "<td>Column $rowNumber</td>";
            echo "<td>";
              echo "Column name: <input required='required' type='text' name='fields[$rowIndex][0]'>";
            echo "</td>";
    
            echo "<td>Column type: <select name='fields[$rowIndex][1]'>
            <option value='int'>INT</option>
            <option value='varchar'>VARCHAR</option>
            <option value='char'>CHAR</option>
            </select></td>";
    
            echo "<td>From Import File Column: <select class='import-column-num' name='fields[$rowIndex][2]'>";
            for($i = 1; $i<=$fileColumns; $i++) {
                echo "<option value='$i'>$i</option>";
            }
            echo "</select></td>";
    
            echo "</tr>";


            //increment the row count
            $_SESSION['import-row']++;

        } else {
            echo "row number not saved in session";
        }

    } else {
        echo "db not set ";
    }
