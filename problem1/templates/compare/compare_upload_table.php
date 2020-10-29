<?php
    session_start();

    if(isset($_FILES['upload-file'])){
        require '../../db_conn.php';

        $fp = $_FILES['upload-file']['tmp_name'];
        $file = fopen($fp, "r");
        $fileName = $_FILES['upload-file']['name'];
        $line = fgets($file);
        $data = str_getcsv($line, ",");
        $fileColumns = count($data);
        
        //original table
        echo "<div>Original Table:</div>";
        echo "<table class='nice-table' id='compare-table-1'>";
        echo "<thead>";
        echo "<tr>";
        for ($x = 0; $x < count($data); $x++) {
            $colNum = $x+1;
            echo "<td >";
            echo "Column $colNum";
            echo "</td>";
          }
        echo "</tr>";
        echo "</thead>";
        echo "</table>";

        //table structure to be uploaded
        echo "<div>Table to be imported as: </div>";
        echo "<table class='nice-table' id='compare-table-2'>";
        echo "<thead>";
        echo "<tr id='import-table-layout'>";
        echo "<td id='0'>";
        echo "Column 1";
        echo "</td>";
        echo "</tr>";
        echo "</thead>";
        echo "</table>";

        fclose($file);



    } else {
        echo "not set ";
    }

