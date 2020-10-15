<?php


    function getUniqueFields($table,$conn) {
        $fields = [];
        $sql = "DESCRIBE $table;";
        
        try {
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result)) {
                if($row['Key'] == 'UNI' || $row['Key'] == 'PRI') {
                    array_push($fields,$row['Field']);
                }
            }
            // array_push($fields,$sql);
            return $fields;
        } catch(Exception $e) {
            echo "$e";
        }

    }


    function getAllFields($table,$conn) {
        $fields = [];
        $sql = "DESCRIBE $table;";
        
        try {
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_assoc($result)) {
                array_push($fields,$row['Field']);
            }
            // array_push($fields,$sql);
            return $fields;
        } catch(Exception $e) {
            echo "$e";
        }

    }


    function getForeignKeyForField($conn,$db,$table) {
        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE REFERENCED_TABLE_SCHEMA = '$db' AND TABLE_NAME = '$table';";
        $fkFields = [];
        $result = mysqli_query($conn,$sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            while($row = mysqli_fetch_array($result)) {
                array_push($fkFields,$row[0]);    
            }
        }
        return $fkFields;

    }

    function getFactTables($tables,$conn){
        $factIdArray = [];
        $factTableArray = [];
        $dimTableArray = [];
        
        foreach($tables as $table) {
            $sql = "select fk_fact_id from dim_table where tb_name = '$table';";
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            if ($resultCheck > 0) {
                while($row = mysqli_fetch_array($result)){
                    if(!in_array($row[0],$factIdArray)) {
                        array_push($factIdArray,$row[0]);
                    }
                }
                //store all dim tables that has fact table relation
                array_push($dimTableArray,$table);
            }

        }

        foreach( $factIdArray as $id) {
            $sql = "select tb_name from fact_table where id = $id;";
            $result = mysqli_query($conn,$sql);
            while($row = mysqli_fetch_array($result)){
                array_push($factTableArray,$row[0]);
            }
        }

        return [$factTableArray,$dimTableArray];

    }


    function describeTable($table,$conn){
        $sql = "DESCRIBE $table;";
        $result = mysqli_query($conn,$sql);
        $columnNumber = mysqli_num_rows($result);
        echo "<table class='nice-table'>";
        echo "<thead>";
            echo "<tr>";
                echo "<th colspan='$columnNumber'>$table</th>";
            echo "</tr>";
        echo "<thead>";
        echo "<tbody>";
            echo "<tr>";
            while($row = mysqli_fetch_array($result)) {
                $field = $row[0];
                echo "<td id='$table.$field'>$field</td>";
            }
            echo "</tr>";
        echo "</tbody>";
        echo "</table>";
        echo "<br>";

    }

    //validate of all tables are related by a fact table
    // return false if fails and return the fact table id if passes
    function validateExportTables($tables,$conn) {

        $factIdArray = [];

        foreach($tables as $table) {
            $sql = "select fk_fact_id from dim_table where tb_name = '$table';";
            $result = mysqli_query($conn,$sql);
            $resultCheck = mysqli_num_rows($result);
            if ($resultCheck > 0) {
                while($row = mysqli_fetch_array($result)){
                    if(key_exists($row[0],$factIdArray)) {
                       $factIdArray[$row[0]]++;
                    } else {
                        $factIdArray[$row[0]] = 1;
                    }
                }
            }
        }

        foreach($factIdArray as $id => $count) {
            if ($count == count($tables)) {
                return $id;
            }
        }

        return false;

    }