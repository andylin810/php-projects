<?php
    session_start();


    require '../db_conn.php';
    require '../sql_functions.php';


    if(isset($_POST['button_name'])){
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    if(isset($_SESSION['database'])){

        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);
        echo "select the star schema fact table: ";
        echo "<form id='select-export-tables' action='tables/select_export_tables.php' method='post'>";

        // $sql = "show tables;";
        // $result = mysqli_query($conn, $sql);
        // $resultCheck = mysqli_num_rows($result);
        // if($resultCheck > 0){
        //     echo "<ul>";
        //     while($row = mysqli_fetch_array($result)){
        //         $name = $row[0];
        //         if($name != "fact_table" && $name != "dim_table") {
        //             echo "<li>";
        //             echo "<input name='selected-tables[]' id='$name' type='checkbox' class='' value='$name'>";
        //             echo "<label for='$name'>$name</label>";
        //             echo "</li>";
        //         }
        //     }
        //     echo "</ul>";
        // }


        $sql = 'select * from fact_table;';
        $result = mysqli_query($conn,$sql);
        echo "<select name='selected-table'>";
            while($row = mysqli_fetch_array($result)){
                $id = $row[0];
                $name = $row[1];
                echo "<option value='$id'>$name</option>";
            }
        echo  "</select>";

        echo '<input type="submit" value="export tables" name="submit">';


        $_SESSION['export-row'] = 1;


        echo "</form>";
    }


    
?>