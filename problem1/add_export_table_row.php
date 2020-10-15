<?php
    session_start();


    require 'db_conn.php';
    require 'sql_functions.php';

    if(isset($_SESSION['database'])){

        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        
        if(isset($_SESSION['export-row'])){
            $rowNumber = $_SESSION['export-row'];

            $tables = $_POST['tables'];
            $fields = $_POST['fields'];

            echo "<tr id='$rowNumber'>";

                //set column name for the export file
                echo "<td>Column Name: <input type='text' name='export-table[$rowNumber][columnName]' /></td>";

                echo "<td> Table: ";
                echo "<select class='export-table-select' id='export-table$rowNumber' name='export-table[$rowNumber][tableName]'>";
                
                foreach($tables as $table) {
                    echo "<option value='$table'>$table</option>";
                }
                echo  "</select>";
                echo "</td>";

                //col to choose field from selected table
                echo "<td> Field: ";
                echo "<select id='export-field$rowNumber' name='export-table[$rowNumber][fieldName]'>";
                
                //get fields from default table
                $fields = getAllFields($tables[0],$conn);

                echo "<option value=''>---</option>";
                foreach($fields as $field) {
                    echo "<option value='$field'>$field</option>";
                }
                echo  "</select>";
                echo "</td>";


                echo "</tr>";


            //increment the row count
            $_SESSION['export-row']++;

        } else {
            echo "row number not saved in session";
        }

    } else {
        echo "db not set ";
    }
