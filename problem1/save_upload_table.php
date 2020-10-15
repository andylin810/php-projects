<?php
    
    if (isset($_POST['submit'])) {
        session_start();

        
        require 'db_conn.php';

        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);
        
            $table_name = $_POST['table-name'];

            $fields = $_POST['fields'];

            $sql = createTable($table_name,$fields[0],$fields[1]);
            $result = mysqli_query($conn, $sql);

            $sql2 = insertTable($table_name,$fields,$fields[0]);
            $result2 = mysqli_query($conn, $sql2);

            // echo insertTable($table_name,$fields,$fields[0]);
            // echo createTable($table_name,$fields[0],$fields[1]);

            if (!$result) {
                header("Location: index.php?error=invaldQuery");
                exit();
            } else {
                header("Location: index.php?success=yes");
                exit();
            }
            header("Location: index.php?success=yes");
            exit();

            //print($sql3);
            

            // header("Location: index.php?success=yes");
            // exit();
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

        //}
        } else {
            header("Location: index.php?error=missDB");
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

    function insertTable($table,$fields,$types){
        $sql = "INSERT INTO $table VALUES ";
        $value = "";
        for($x = 2; $x < count($fields); $x++) {
            $value .= "(";
            foreach($fields[$x] as $index=>$field) {
                $value .= $types[$index] == 'int' ? "$field," : "'$field',";
            }
            $value = substr($value, 0, -1);
            $value .= "),";
        }
        $value = substr($value, 0, -1);
        $sql .= $value . ";";
        return $sql;
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