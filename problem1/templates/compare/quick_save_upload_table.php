<?php
    
    if (isset($_POST['submit'])) {
        session_start();

        
        require '../../db_conn.php';

        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);
        
            $table_name = $_POST['table-name'];

            $fields = $_POST['fields'];

            //open file
            $fp = $_FILES['upload-file']['tmp_name'];
            $fileFullName = $_FILES['upload-file']['name'];
            $fileName = substr($fileFullName, 0, -4);


            //new stuff
            $colNames = [];
            $colTypes = [];
            $importColumns = [];

            foreach($fields as $field) {
                array_push($colNames,$field[0]);
                array_push($colTypes,$field[1]);
                array_push($importColumns,$field[2]);
            }

            //column order reference
            $colOrder = "";
            foreach($importColumns as $col) {
                $colOrder .= $col;
            }
           
            //new stuff

            $sql = createTable($table_name,$colTypes,$colNames);
            $result = mysqli_query($conn, $sql);

            // $sql2 = insertTable($table_name,$fields,$fields[0],$fp);
            // $result2 = mysqli_query($conn, $sql2);

            
            insertTable($conn,$table_name,$fields,$colTypes,$importColumns,$fp);
            createLayoutTable($conn,$table_name,$fileName,$colOrder);
            // echo createTable($table_name,$fields[0],$fields[1]);
            // echo insertTable($table_name,$fields,$fields[0],$fp);


            if (!$result) {
                header("Location: /?error=invaldQuery");
                exit();
            } else {
                header("Location: /?success=yes");
                exit();
            }
            header("Location: /?success=yes");
            exit();

        } else {
            header("Location: /?error=missDB");
            exit();
        }
    }


    function createTable($table,$types,$values) {
        $sql = "CREATE TABLE $table (";
        $value = "";
        for ($i = 0; $i < count($types); $i++) {
            $name = $values[$i];
            $type = $types[$i];
            switch ($type){
                case "varchar":
                    $type = "$type(255)";
                break;
                case "char" :
                    $type = "$type(50)";
                break;
            }
            $value .= "$name $type,";
          }
        $value = substr($value, 0, -1);
        $sql .= $value . ");";
        return $sql;
    }

    function insertTable($conn,$table,$fields,$types,$importColumns,$fp){

        //open the file
        $file = fopen($fp, "r");

        // $sql = "INSERT INTO $table VALUES ";
        // $value = "";
        //read file line by line

        if(is_uploaded_file($_FILES['upload-file']['tmp_name'])){

        

            while ( !feof($file) )
            {
                $sql = "INSERT INTO $table VALUES ";
                $value = "";

                // $line = fgets($file);

                // $data = str_getcsv($line, ",");
                $count = 0;

                while($count < 1000 && !feof($file)) {
                    $line = fgets($file);
                    $data = str_getcsv($line, ",");

                    $value .= "(";

                    //previous working
                    // foreach($data as $index=>$field){
                    //     $value .= $types[$index] == 'int' ? "$field," : "'$field',";
                    // }

                    //new stuff
                    foreach($importColumns as $i){
                        $index = $i-1;
                        $val = $data[$index];
                        $value .= $types[$index] == 'int' ? "$val," : "'$val',";
                    }

                    $value = substr($value, 0, -1);
                    $value .= "),";
                    $count++;
                }
                $value = substr($value, 0, -1);
                $sql .= $value . ";";
                $result = mysqli_query($conn, $sql);
            }   
            // $value = substr($value, 0, -1);
            // $sql .= $value . ";";
            fclose($file);
            // return $sql;
        } else {
            echo "not uploaded";
        }
    }

    function createLayoutTable($conn,$tableName,$fileName,$colOrder) {
        $sql = "INSERT INTO import_layout (tb_name,export_file_name,col_order) values ('$tableName','$fileName','$colOrder');";
        $result = mysqli_query($conn,$sql);
    }

?>