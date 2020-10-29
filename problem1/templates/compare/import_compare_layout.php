<?php
    session_start();
    if(isset($_SESSION['database'])){
    
        require '../../db_conn.php';
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        $table = $_POST['table_name'];
        if(isset($_SESSION['col-order'])){
            $colOrder .= $_SESSION['col-order'];
            $columnReference = str_split($colOrder);

            echo "<thead>";
            echo "<tr id='import-table-layout'>";
            foreach($columnReference as $i => $col) {
                echo "<td id='$i'>";
                echo "Column $col";
                echo "</td>";
            }
            echo "</tr>";
            echo "</thead>";

            echo var_export($columnReference);
        } else {
            echo "column order not set";
        }

    } else {
        echo 'db not set';
    }