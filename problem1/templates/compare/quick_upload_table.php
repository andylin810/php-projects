<?php
    session_start();

    if(isset($_FILES['upload-file']) && isset($_SESSION['database']) ){
        require '../../db_conn.php';
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        $_SESSION['import-row'] = 1;

        $fp = $_FILES['upload-file']['tmp_name'];
        $file = fopen($fp, "r");
        $fileFullName = $_FILES['upload-file']['name'];
        $fileName = substr($fileFullName, 0, -4);
        $line = fgets($file);
        $data = str_getcsv($line, ",");
        $fileColumns = count($data);
        
        echo getLayoutSelect($conn,$fileName);
        echo "table name: <input required='required' type='text' name='table-name'>";
        echo "<input type='hidden' id='file-length' value='$fileColumns'>";
        echo "<input class='add-import-row' type='button' value='add row'>";
        echo "<input class='delete-import-row' type='button' value='delete row'>";
        echo "<table id='import-file-table'>";

        //new version
        echo "<tr id='0'>";
        echo "<td>Column 1</td>";
        echo "<td>";
          echo "Column name: <input required='required' type='text' name='fields[0][0]'>";
        echo "</td>";

        echo "<td>Column type: <select name='fields[0][1]'>
        <option value='int'>INT</option>
        <option value='varchar'>VARCHAR</option>
        <option value='char'>CHAR</option>
        </select></td>";

        echo "<td>From Import File Column: <select class='import-column-num' name='fields[0][2]'>";
        foreach($data as $index => $col) {
            $colNum = $index+1;
            echo "<option value='$colNum'>$colNum</option>";
        }
        echo "</select></td>";

        echo "</tr>";

        echo "</table>";
        echo "<input value='save table' type='submit' name='submit'>";
        echo "</form>";

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////
        echo "<div>data preview: </div>";
        echo "<table class='nice-table'>";

        //file data preview and showing column number
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

        echo "<tr>";
        foreach($data as $field) {
            echo "<td>";
            echo $field;
            echo "</td>";
          }
        echo "</tr>";
        echo "</table>";
      

        fclose($file);



    } else {
        echo "not set ";
    }

    function getLayoutSelect($conn,$fileName) {
      $tableArr= [];
      $sql = "SELECT tb_name from import_layout where export_file_name = '$fileName';";
      $result = mysqli_query($conn,$sql);
      while($row = mysqli_fetch_assoc($result)) {
        array_push($tableArr,$row['tb_name']);
      }
      $selectString = "<div>Select existing layouts: <select class='layout-select' name=''><option value=''>---</option>";

      foreach($tableArr as $table) {
        $selectString .= "<option value='$table'>$table</option>";
      }
      $selectString .= "</select><input class='import-layout' type='button' value='import layout'></div>";
      return $selectString;
    }

