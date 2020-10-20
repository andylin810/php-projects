<?php

    /**
     * This script gets table name and displays data from the table.
     */

    session_start();
    require '../../db_conn.php';
    
    if (isset($_SESSION['database'])) {
        mysqli_select_db($conn, $_SESSION['database']);
        $table = $_POST['table_name'];

        // Display data from selected table
        $result = mysqli_query($conn, "SELECT * FROM $table LIMIT 100;");
        $header_info = mysqli_fetch_fields($result);


        // Get the data from the query and display the table with HTML table
        // Display table field names from the first row
        echo "<thead>";
        echo "<tr>";
        foreach ($header_info as $val) {
            echo "<th>$val->name</th>";
        }
        echo "</tr>";
        echo "</thead>";

        // Fetching the result of the query and displaying records
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