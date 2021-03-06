<?php

    /**
     * This file processes the form submission and link all fields of the dimension tables 
     * to the fields of the selected fact table by adding foreign keys to the fact table fields
     * and reference them to the corresponding fields.
     */

    session_start();
    require '../../db_conn.php';
    if(isset($_POST['submit'])){
        if(isset($_SESSION['database']) && isset($_POST['dim-table'])) {
            mysqli_select_db($conn,$_SESSION['database']);
            
            $dimTableData = $_POST['dim-table'];
            $factColumns = $dimTableData[0];
            $tables = $dimTableData[1];
            $fields = $dimTableData[2];
            $factTable = $_POST['fact-table'];

            try {
                linkTables($conn,$tables,$factTable,$fields,$factColumns);
                header("Location: /?page=star_schema&link=success");
            } catch (Throwable $e) {
                $msg = $e->getMessage();
                header("Location: /?page=star_schema&link=fail&error=$msg");
            }
            
        } else {
            echo "db not selected or no data is sent ";
        }
    } else {
        echo "did not submit form";
    }

    /**
     * Link fact table to dimension tables by adding foreign keys that reference the 
     * selected fields.
     * 
     * @param Mysqli $conn Mysqli connection object
     * @param string[] $tables Array of dimention table names being linked to
     * @param string $factTb Fact table name
     * @param string[] $fields Array of field names that are being linked to
     * @param string[] $factColumns Fact table column names
     * @return void Establish foreign key relationships and add all dimension tables to the
     * table in database for storing all dimension tables and add the fact table to the table in database
     * for storing all fact tables
     */

    function linkTables($conn,$tables,$factTb,$fields,$factColumns) {
        mysqli_begin_transaction($conn);
        
        try {
            //check if this table is already in the fact table, if not insert it
            $sql = "select 1 from fact_table where tb_name = '$factTb'";
            $result = mysqli_query($conn,$sql);

            //table exists
            if(mysqli_num_rows($result) == 0) {
                $sql = "insert into fact_table (tb_name) values ('$factTb')";
                $result = mysqli_query($conn,$sql);
                if(!$result) {
                    throw new Exception('query failed');
                }
            }

            foreach($fields as $index=>$field) {
                if($field) {
                    //linking table with foreign key
                    $factCol = $factColumns[$index];
                    $table = $tables[$index];
                    $sql = "ALTER TABLE $factTb ADD FOREIGN KEY ($factCol) REFERENCES $table($field) ON UPDATE CASCADE ON DELETE CASCADE";
                    $result = mysqli_query($conn,$sql);

                    //after linking, add table to dimension table list 
                    //check if this table is already in the fact table, if not insert it
                    $sql2 = "select 1 from dim_table where tb_name = '$table' and fk_fact_id = (select id from fact_table
                    where tb_name = '$factTb');";
                    $result2 = mysqli_query($conn,$sql2);

                    //table exists
                    if(mysqli_num_rows($result2) == 0) {
                        $sql = "insert into dim_table (tb_name,fk_fact_id) values ('$table',(select id from fact_table
                        where tb_name = '$factTb'));";
                        $insertResult = mysqli_query($conn,$sql);
                        if(!$insertResult) {
                            throw new Exception('query failed');
                        }
                    }
                    if(!$result) {
                        throw new Exception('query failed');
                    }
                }
    
            }

            // save changes
            mysqli_commit($conn);

        } catch (Throwable $e) {
            mysqli_rollback($conn);
            throw $e;
        }

    }

    