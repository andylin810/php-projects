<?php
    
    if (isset($_POST['submit'])) {
        session_start();

        
        require 'db_conn.php';

        if(isset($_SESSION['database'])){

            mysqli_select_db($conn, $_SESSION['database']);
        
            $table_name = $_POST['table_name'];

            $fields = $_POST['fields'];

            $sql = makeQuery($fields,$table_name);
            $result = mysqli_query($conn, $sql);
            if (!$result) {
                header("Location: index.php?error=invaldQuery");
                exit();
            } else {
                header("Location: index.php?success=yes");
                exit();
            }

            //print($sql3);
            

            header("Location: index.php?success=yes");
            exit();
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


    function makeQuery($fields, $table) {
        $sql = "CREATE TABLE $table (";
        foreach($fields as $index=>$field) {
            $name = $field['field_name'];
            $type = $field['type'];
            $len = $field['length'];
            $length = $len ? "($len)" : "" ;
            $null = $field['null']? "not null" : "";
            $pk = $field['pk']? " primary key" : "";
            if ($index == count($fields)-1) {
                $sql .= "$name $type $length $pk $null);";
            } else {
                $sql .= "$name $type $length $pk $null,";
            }
        }

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