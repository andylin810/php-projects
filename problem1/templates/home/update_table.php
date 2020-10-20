<?php

    /**
     * This script update the table and save the change in the database.
     */
    require '../../db_conn.php';


    session_start();
    
    if (isset($_SESSION['database'])) {
        mysqli_select_db($conn, $_SESSION['database']);

        // Get table name and fields from post parameters
        $table = $_POST['table_name'];
        $data = $_POST['table_data'];

        $result = mysqli_query($conn, "DESCRIBE $table;");

        // Get sql query for updating table from function
        $sql = updateField($data,$table,$result);

        // Execute the sql query to update table
        $result = mysqli_query($conn, $sql);
        if(!$result) {
            echo "error";
        } else {
            echo $sql;
        }

    
    }
   
    /**
     * Accepts a table field array, table name and descibe table query result
     * return a sql query for updating the table with the fields passed in
     * 
     * @param mixed[] $fieldArray Assoc array of field names and field values
     * @param string $tableName Table name
     * @param Mysqli_result $result Mysqli query result object
     * @return string Returns the sql query string for updating a row
     */
    
    function updateField($fieldArray,$tableName,$result) {
        if(!empty($fieldArray)) {
            $fVal = $_POST['f_val'];

            // Get the type of the first field, if not int add quotes to the query string
            // This is for the WHERE clause
            $f_type = mysqli_fetch_array($result)[1];
            mysqli_data_seek($result,0);

            $f_key = $fVal['key'];
            $f_val = checkNum($fVal['val'],$f_type);
            $sql = "UPDATE $tableName SET ";
            $stmt = "";

            $type = "";

            // Update every row with data passed in 
            foreach($fieldArray as $field) {
                $col = $field['key'];
                $val = $field['val'];

                $row = mysqli_fetch_assoc($result);
                $rowType = $row['Type'];

                if($val == "") {
                    $val = "null";
                } else {
                    if($rowType!='int') {
                        $val = "'$val'";
                    }
                }
                $stmt .= "$col = $val,";
            }
            $stmt = substr($stmt, 0, -1);
            $whereStmt = " WHERE $f_key = $f_val;";
            $sql .= $stmt . $whereStmt;
            
            return $sql;
        }
    }

    /**
     * Add quotes to string, if the type of the field is not INT
     * for writing sql query.
     * 
     * @param string $name Field name
     * @param string $type Field type
     * @return string Returns the name with quotes if its type is not INT
     */
    function checkNum($name,$type) {
        if($type != 'int') {
            $name = "'$name'";
        }
        return $name;
    }

            

 

?>