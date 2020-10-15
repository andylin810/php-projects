<?php
 require 'db_conn.php';


 session_start();
 // session_unset();   //deletes session data
 // session_destroy(); //deletes session 
 
 if (isset($_SESSION['database'])) {
     mysqli_select_db($conn, $_SESSION['database']);
     $table = $_POST['table_name'];

    $result = mysqli_query($conn, "SELECT * FROM $table LIMIT 100;");
    $header_info = mysqli_fetch_fields($result);


    //header
    echo "<thead>";
    echo "<tr>";
    foreach ($header_info as $val) {
        echo "<th>$val->name</th>";
    }
    echo "</tr>";
    echo "</thead>";

    //fields

    // while($field_info = mysqli_fetch_array($result, MYSQLI_NUM)) {
    //     echo "<tr>";
    //     foreach($field_info as $field) {
    //         echo "<td contenteditable='true'>$field</td>";
    //     }
    //     echo "</tr>";
    // }
    echo "<tbody>";
    while($field_info = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        $num = 0;
        foreach($field_info as $key=>$field) {
            // echo "<td class='$num' data-value='$field' contenteditable='true' value='$key'>$field</td>";
            echo "<td><span class='$num' data-value='$field' contenteditable='true' value='$key'>$field</span></td>";
            $num++;
        }
        echo "</tr>";
        
    }
    echo "</tbody>";
 }

            

 

?>