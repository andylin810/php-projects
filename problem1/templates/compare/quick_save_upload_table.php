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
            
            
            $sql = createTable($table_name,$fields[0],$fields[1]);
            $result = mysqli_query($conn, $sql);

            // $sql2 = insertTable($table_name,$fields,$fields[0],$fp);
            // $result2 = mysqli_query($conn, $sql2);

            
            insertTable($conn,$table_name,$fields,$fields[0],$fp);
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

    function insertTable($conn,$table,$fields,$types,$fp){

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
                    foreach($data as $index=>$field){
                        $value .= $types[$index] == 'int' ? "$field," : "'$field',";
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

        // $stmt = mysqli_stmt_init($conn);
        // if (!mysqli_stmt_prepare($stmt,$sql)){
        //     header("Location: index.php?error=someerror");
        //     exit();
        // } else {
        //     mysqli_stmt_bind_param($stmt,"s",$length );
        //     mysqli_stmt_execute($stmt);
        //     mysqli_stmt_store_result($stmt);
        //     $result = mysqli_stmt_num_rows($stmt);
        // }
?>