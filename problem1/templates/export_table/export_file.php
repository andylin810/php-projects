<?php

    session_start();


    require '../../db_conn.php';
    require '../../sql_functions.php';

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        if(isset($_POST['export-table'])) {
            $tableRows = $_POST['export-table'];
            $factTableId = $_POST['fact-table'];
            $fileName = $_POST['file-name'];


            $sql = "select tb_name from fact_table where id = $factTableId;";
            $result = mysqli_query($conn,$sql);

            //fact table name
            $factTable = mysqli_fetch_array($result)[0];

            //array contains assoc arrays for columns of the export table 
            //including col-name, table, table.field
            $exportColumns = [];

            foreach($tableRows as $row) {
                $rowData = [];
                foreach($row as $key => $field) {
                    $rowData[$key] = $field;
                }
                array_push($exportColumns,$rowData);
            }

            //corresponding fact table column name and dim column name
            $factDimRelatedColumns = [];
            $uniqueDimTables = [];

            foreach($exportColumns as $column) {
                $table = $column['tableName'];
                $field = $column['fieldName'];
                $sql = "SELECT column_name, referenced_column_name FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
                WHERE table_name = '$factTable' AND REFERENCED_TABLE_SCHEMA = '$db' AND referenced_table_name = '$table';";
                $result = mysqli_query($conn,$sql);
                while($row = mysqli_fetch_array($result)) {
                    if(!in_array($table,$uniqueDimTables)) {
                        array_push($uniqueDimTables,$table);
                        //assoc array includes dim-col and fact-col
                        $arr = [];
                        $arr['fact-col'] = $row[0];
                        $arr['dim-col'] = $row[1];

                        $factDimRelatedColumns[$table] = $arr;
                    }
                }

            }
            $sql = joinDimTables($conn,$exportColumns,$factDimRelatedColumns,$uniqueDimTables,$factTable);
            exportFile($fileName,$sql,$exportColumns,$conn);
            // echo $sql;




            header("Location: /");
        } else {
            echo "no table data";
        }






    } else {
        echo "db not set";
    }




    function exportFile($fileName,$sql,$exportColumns,$conn) {
        $result = mysqli_query($conn,$sql);
        $fh = fopen("$fileName.txt", 'w');
        $colCount = count($exportColumns);
        $sqlRowCount = mysqli_num_rows($result);
        $count = 0;

        //write first row to file
        foreach($exportColumns as $index=>$col) {
            $columnName = $col['columnName'];
            fwrite($fh, $columnName);
            if ($index != $colCount-1)
                fwrite($fh, ",");
        }
        fwrite($fh, "\n");

        //write data from selected tables to file
        while($row = mysqli_fetch_array($result,MYSQLI_NUM)) {
            foreach($row as $index=>$field) {
                fwrite($fh, $field);
                if ($index != $colCount-1)
                fwrite($fh, ",");
            }
            if ($count != $sqlRowCount-1)
            fwrite($fh, "\n");
            $count++;
        }
        fclose($fh);
    }

    function joinDimTables($conn,$exportColumns,$factDimRelatedColumns,$uniqueDimTables,$factTable) {
        $columns = '';
        $joins = '';

        //get columns of export table
        foreach($exportColumns as $col) {
            $tableName = $col['tableName'];
            $columnName = $col['fieldName'];

            $columns .= "$tableName.$columnName,";
        }
        $columns = substr($columns, 0, -1);

        
        foreach($uniqueDimTables as $dimTable) {
            $dimTableField = $factDimRelatedColumns[$dimTable]['dim-col'];
            $factField = $factDimRelatedColumns[$dimTable]['fact-col'];

            $join  = " join $dimTable on $dimTable.$dimTableField = $factTable.$factField ";
            $joins .= $join;
        }

        $sql = "SELECT $columns from $factTable $joins;";

        return $sql;


    }




?>



