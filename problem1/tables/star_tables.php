<?php
    session_start();

    require '../db_conn.php';
    require '../sql_functions.php';

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        if(isset($_POST['selected-tables'])) {
            $tables = $_POST['selected-tables'];
            $allDimTables = getFactTables($tables,$conn)[1];
            echo "Dimension tables: ";
            foreach($allDimTables as $key=>$table) {
                describeTable($table,$conn);
            }


            $factTables = getFactTables($tables,$conn)[0];
            echo "Fact tables: ";
            foreach($factTables as $key=>$table) {
                //get columns of fact table
                $factColumns = [];

                $sql = "DESCRIBE $table;";
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_array($result)) {
                    array_push($factColumns,$row[0]);
                }


                // echo $table;
                // describeTable($table,$conn);
                $columns = [];
                $dimTables = [];
                $dimColumns = [];

                //get infor for fact table and dim tables related to it
                $sql = displayFactTableSql($table,$db);
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_assoc($result)) {
                    array_push($columns,$row['COLUMN_NAME']);
                    array_push($dimTables,$row['REFERENCED_TABLE_NAME']);
                    array_push($dimColumns,$row['REFERENCED_COLUMN_NAME']);
                }

                echo "<table class='nice-table'>";
                echo "<thead>";
                    echo "<tr>";
                    $colWidth = count($factColumns);
                        echo "<th colspan='$colWidth'>$table</th>";
                    echo "</tr>";
                echo "<thead>";
                echo "<tbody>";
                    //display column names for fact table
                    echo "<tr>";
                    foreach($factColumns as $column) {
                        echo "<th>$column</th>";
                    }
                    echo "</tr>";

                    //display if there is relationship between columns of dimension 
                    // tables
                    echo "<tr>";
                    foreach($factColumns as $index => $column) {
                        $tableLink = $dimTables[$index] . "." . $dimColumns[$index];
                        if (in_array($columns[$index],$factColumns)) {
                            echo in_array($dimTables[$index],$tables)? "<td class='highlight-dim'>$tableLink</td>" : "<td>$tableLink</td>";
                        }  else {
                            echo "<td>N/A</td>";
                        }
                    }
                    echo "</tr>";
                echo "</tbody>";
                echo "</table>";


            }


        } else {
            echo "tables not sent";
        }

    } else {
        echo "db not set";
    }



    function displayFactTableSql($table,$db) {

        $sql = "SELECT COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME
      FROM
        INFORMATION_SCHEMA.KEY_COLUMN_USAGE
      WHERE
        REFERENCED_TABLE_SCHEMA = '$db' AND
        TABLE_NAME = '$table';";

        return $sql;
    }