<?php
    if(isset($_FILES['upload-file'])){
        require 'db_conn.php';

        $file = fopen($_FILES['upload-file']['tmp_name'], "r");
        $fileName = $_FILES['upload-file']['name'];

        echo "<form id='save-upload-form' action='save_upload_table.php' method='post'>";
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


        rewind($file);
        $num = 2;

        //displaying data for each field
        while ( !feof($file) )
        {
            $line = fgets($file);

            $data = str_getcsv($line, ",");

            echo "<tr>";
            foreach($data as $index=>$field){
                echo "<td class='$index'>";
                echo "<input value='$field' type='text' name='fields[$num][$index]'>";
                echo "</td>";
            }
            echo "</tr>";
            $num++;
        }                              
        echo "</table>";
        echo "<input value='save table' type='submit' name='submit'>";
        echo "</form>";
        fclose($file);



    } else {
        echo "not set ";
    }

