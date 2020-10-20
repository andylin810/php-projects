<?php
    session_start();

    if(isset($_FILES['upload-file'])){
        require '../../db_conn.php';

        $fp = $_FILES['upload-file']['tmp_name'];
        $file = fopen($fp, "r");
        $fileName = $_FILES['upload-file']['name'];
        

        echo "table name: <input type='text' name='table-name'>";
        echo "<table>";
        $line = fgets($file);
        $data = str_getcsv($line, ",");

        //allow selecting field type for table
        echo "<tr>";
        for ($x = 0; $x < count($data); $x++) {
            echo "<th>";
            echo "<select name='fields[0][$x]'>
                        <option value='int'>INT</option>
                        <option value='varchar'>VARCHAR</option>
                        <option value='char'>CHAR</option>
                    </select>";
            echo "</th>";
          }
        echo "</tr>";

        //allow inserting field name for table
        echo "<tr>";
        for ($x = 0; $x < count($data); $x++) {
            echo "<td>";
            echo "<input type='text' name='fields[1][$x]'>";
            echo "</td>";
          }
        echo "</tr>";
      
        echo "</table>";
        echo "<input value='save table' type='submit' name='submit'>";
        echo "</form>";
        fclose($file);



    } else {
        echo "not set ";
    }

