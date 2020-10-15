<?php
 require 'db_conn.php';


 session_start();
 // session_unset();   //deletes session data
 // session_destroy(); //deletes session 
 
 if (isset($_SESSION['database'])) {
    mysqli_select_db($conn, $_SESSION['database']);
    $table = $_POST['table_name'];
    $data = $_POST['table_data'];

    $result = mysqli_query($conn, "DESCRIBE $table;");



    $sql = updateField($data,$table,$result);

    $result = mysqli_query($conn, $sql);
    if(!$result) {
        echo "error";
    } else {
        echo $sql;
    }

 


    // //header
    // echo "<tr>";
    // foreach ($header_info as $val) {
    //     echo "<th>$val->name</th>";
    // }
    // echo "</tr>";

    // //fields

    // while($field_info = mysqli_fetch_array($result, MYSQLI_NUM)) {
    //     echo "<tr>";
    //     foreach($field_info as $field) {
    //         echo "<td>$field</td>";
    //     }
    //     echo "</tr>";
    // }
 }

 function updateField($fieldArray,$tableName,$result) {
    if(!empty($fieldArray)) {
        $fVal = $_POST['f_val'];
        //get the type of the first field
        $f_type = mysqli_fetch_array($result)[1];
        mysqli_data_seek($result,0);

        $f_key = $fVal['key'];
        $f_val = checkNum($fVal['val'],$f_type);
        $sql = "UPDATE $tableName SET ";
        $stmt = "";

        $type = "";

        
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

function checkNum($name,$type) {
    if($type != 'int') {
        $name = "'$name'";
    }
    return $name;
}

            

 

?>