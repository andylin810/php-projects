<?php
    
    if (isset($_POST['submit'])) {
        session_start();

        //connect to db
        require '../../db_conn.php';


        // if database is selected, get input data from html form and query for
        // create a table and save in database
        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);
        
            $table_name = $_POST['table_name'];

            $fields = $_POST['fields'];

            // construct query from inputs
            $sql = makeQuery($fields,$table_name);
            $result = mysqli_query($conn, $sql);

            // if result succeeds redirect to home page with success parameter
            // otherwise send error parameter
            if (!$result) {
                header("Location: /?error=invaldQuery");
                exit();
            } else {
                header("Location: /?success=yes");
                exit();
            }

            //print($sql3);
            

            header("Location: /?success=yes");
            exit();

        } else {
            header("Location: /?error=missDB");
            exit();
        }
    }

    /**
     * function that accepts fields and table name, return a sql query that
     * create a table with the specified field and save it to the database.
     * 
     * @param mixed[] $field Assoc array of table field properties
     * @param string $table Table name
     * @return string Returns the sql statement for creating table
     */
    function makeQuery($fields, $table) {
        $sql = "CREATE TABLE $table (";
        foreach($fields as $index=>$field) {
            $name = $field['field_name'];
            $type = $field['type'];
            $len = $field['length'];
            $length = $len ? "($len)" : "" ;
            $null = $field['null']? "not null" : "";
            $pk = $field['pk']? " primary key" : "";
            if ($index == count($fields)-1) {
                $sql .= "$name $type $length $pk $null);";
            } else {
                $sql .= "$name $type $length $pk $null,";
            }
        }

        return $sql;
    }

?>