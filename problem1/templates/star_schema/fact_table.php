<?php

    /**
     * This file displays the fields of the table being selected as fact table, allowing each of the field 
     * to be linked to a field from another table as foreign keys, select tag will be disabled if it's already
     * linked.
     */

    require '../../db_conn.php';
    require '../../sql_functions.php';

    session_start();
    if($_SERVER['REQUEST_METHOD'] == 'POST'){

        if(isset($_SESSION['database'])){

            //connect to db
            $tableName = $_POST['fact-table'];
            $db = $_SESSION['database'];
            $fkFields = getForeignKeyForField($conn,$db,$tableName);
            mysqli_select_db($conn, $db);

            echo "Please select the dimension tables:";
            echo "<form id='connect-tables' action='templates/star_schema/link_tables.php' method='post'>";

            //show fact table fields
            $sql = "DESCRIBE $tableName;";
            $result = mysqli_query($conn,$sql);
            $fields = [];
            $columnCount = mysqli_num_rows($result);
            $defaultTables= [];

            //send fact table data
            echo "<input type='hidden' name='fact-table' value='$tableName' />";


            while($row = mysqli_fetch_array($result)){
                array_push($fields,$row[0]); 
            }

            echo "<table class='nice-table'>";

            echo "<thead>";
            echo "<tr>";
                foreach($fields as $field) { 
                    echo "<th>$field</th>";       
                }
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            //row to select dimenstion table
            echo "<tr>";
                $sql_table = "show tables;";
                $table_result = mysqli_query($conn, $sql_table);
                for($x = 0; $x < $columnCount; $x++) {
                    echo "<td> table: ";
                    if(in_array($fields[$x], $fkFields)){
                        echo "<select disabled='true' data-col='$x' class='select-dim' name='dim-table[1][$x]'>";
                    } else {
                        $colName = $fields[$x];
                        echo "<input type='hidden' name='dim-table[0][$x]' value='$colName' />";
                        echo "<select data-col='$x' class='select-dim' name='dim-table[1][$x]'>";
                    }
                    $y = 0;
                    while($row = mysqli_fetch_array($table_result)){
                        //table name
                        $name = $row[0];
                        if($name != "fact_table" && $name != $tableName && !empty(getUniqueFields($name,$conn))) {
                            echo "<option value='$name'>$name</option>";
                            if($y == 0) {
                                array_push($defaultTables,$name);
                            }
                            $y++;
                        }
                        
                    }
                    mysqli_data_seek($table_result,0);
                    echo  "</select>";
                    echo "</td>";
                }
            echo "</tr>";

            //row to select column for dimension table
            echo "<tr>";
                for($x = 0; $x < $columnCount; $x++) {
                    //get unique fields for default tables
                    $tableName = $defaultTables[$x];
                    $uniqueFields = getUniqueFields($tableName,$conn);
                    echo "<td> field: ";
                    if(in_array($fields[$x], $fkFields)){
                        echo "<select disabled='true' id='dim-field$x' name='dim-table[2][$x]'>";
                    } else {
                        echo "<select id='dim-field$x' name='dim-table[2][$x]'>";
                    }
                    
                    echo "<option value=''>---</option>";
                    foreach($uniqueFields as $field) {
                        echo "<option value='$field'>$field</option>";
                    }
                    echo  "</select>";
                    echo "</td>";
                }
            echo "</tr>";




            $sql = "show tables;";
            $result = mysqli_query($conn, $sql);
            $resultCheck = mysqli_num_rows($result);
            if($resultCheck > 0){
               
            }
            echo "</tbody>";
            echo "</table>";
            echo "<input name='submit' type='submit' value='link tables'>";


            echo "</form>";
        } 
    } else {
        echo "not set i think ";
    }