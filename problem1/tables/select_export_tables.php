<?php
    session_start();

    require '../db_conn.php';
    require '../sql_functions.php';

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        if(isset($_POST['selected-table'])) {
            // $tables = $_POST['selected-tables'];
            $factId = 0;

            $factId= $_POST['selected-table'];
            $dimTables = getDimTableFromFact($factId,$conn);

            // $id = validateExportTables($tables,$conn);
            // if($id) {
                // $factId = $id;

                echo "Please select the fields you would like to export:";
                echo "<form id='export-table' action='templates/export_table/export_file.php' method='post'>";

                //export file name 
                echo "Export File Name: ";
                echo "<input required='required' type='text' name='file-name' />";

                //add additional row
                echo "<input class='add-export-row' type='button' value='add row'>";
                echo "<input class='delete-export-row' type='button' value='delete row'>";
                echo "set max row: <input class='set-max-row' name='max-row' type='number'>";

                //send fact table data
                echo "<input type='hidden' name='fact-table' value='$factId' />";
                echo "<table id='export-file-table'>";

                //col to choose table from selected tables
                echo "<tr id='0'>";

                //set column name for the export file
                echo "<td>Column Name: <input required='required' type='text' name='export-table[0][columnName]' /></td>";

                echo "<td> Table: ";
                echo "<select class='export-table-select' id='export-table0' name='export-table[0][tableName]'>";
                
                foreach($dimTables as $table) {
                    echo "<option value='$table'>$table</option>";
                }
                echo  "</select>";
                echo "</td>";

                //col to choose field from selected table
                echo "<td> Field: ";
                echo "<select required='required' id='export-field0' name='export-table[0][fieldName]'>";
                
                //get fields from default table
                $fields = getAllFields($dimTables[0],$conn);

                echo "<option value=''>---</option>";
                foreach($fields as $field) {
                    echo "<option value='$field'>$field</option>";
                }
                echo  "</select>";
                echo "</td>";


                echo "</tr>";





                // $sql = "show tables;";
                // $result = mysqli_query($conn, $sql);
                // $resultCheck = mysqli_num_rows($result);
                // if($resultCheck > 0){
                
                // }
                echo "</tbody>";
                echo "</table>";
                echo "<input name='submit' type='submit' value='export'>";
                


                echo "</form>";

            // } else {
            //     echo "all selected tables have to be related!";
            // }
            


        } else {
            echo "tables not sent";
        }

    } else {
        echo "db not set";
    }

    function getDimTableFromFact($factId,$conn) {
        $dimTables = [];
        $sql = "SELECT tb_name from dim_table where fk_fact_id = $factId;";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_array($result)) {
            array_push($dimTables,$row[0]); 
        }
        return $dimTables;

    }
