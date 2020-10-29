<?php
    session_start();
    if(isset($_SESSION['database'])){
    
        require '../../db_conn.php';
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        $table = $_POST['table_name'];
        $columnNames = [];
        $columnTypes = [];
        $columnReference = [];

        $sql = "DESCRIBE $table;";
        $result = mysqli_query($conn,$sql);
        while($row = mysqli_fetch_assoc($result)) {
            array_push($columnNames,$row['Field']);
            array_push($columnTypes,$row['Type']);
        }

        $sql = "SELECT col_order from import_layout where tb_name = '$table';";
        $result = mysqli_query($conn,$sql);
        $colOrder = mysqli_fetch_array($result)[0];
        $_SESSION['col-order'] = $colOrder;

        $columnReference = str_split($colOrder);
        $arrSize = count($columnNames);
        $_SESSION['import-row'] = $arrSize;

        for($i = 0; $i<$arrSize; $i++) {
            $colNumber = $i+1;
            $name = $columnNames[$i];
            $type = $columnTypes[$i];
            $reference = $columnReference[$i];

            echo "<tr id='$i'>";
            echo "<td>Column $colNumber</td>";
            echo "<td>";
            echo "Column name: <input value='$name' required='required' type='text' name='fields[$i][0]'>";
            echo "</td>";

            echo "<td>Column type: <select name='fields[$i][1]'>";
            switch($type) {
                case "int": 
                    echo 
                    "<option selected value='int'>INT</option>
                    <option value='varchar'>VARCHAR</option>
                    <option value='char'>CHAR</option>
                    </select></td>";
                break;
                case "varchar(255)": 
                    echo 
                    "<option value='int'>INT</option>
                    <option selected value='varchar'>VARCHAR</option>
                    <option value='char'>CHAR</option>
                    </select></td>";
                break;
                default:
                    echo 
                    "<option value='int'>INT</option>
                    <option value='varchar'>VARCHAR</option>
                    <option selected value='char'>CHAR</option>
                    </select></td>";
            }


            echo "<td>From Import File Column: <select class='import-column-num' name='fields[$i][2]'>";
            for($x= 1; $x <= $arrSize; $x++) {
                echo $reference == $x ? "<option selected value='$x'>$x</option>" : "<option value='$x'>$x</option>";
            }
            echo "</select></td>";

            echo "</tr>";
        }




    }


           