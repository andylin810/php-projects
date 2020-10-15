<?php
    session_start();


    require 'db_conn.php';
    require 'sql_functions.php';


    if(isset($_POST['button_name'])){
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    if(isset($_SESSION['database'])){

        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);

        echo "<form id='select-dim-tables' action='tables/star_tables.php' method='post'>";
        $sql = "show tables;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            echo "<ul>";
            while($row = mysqli_fetch_array($result)){
                $name = $row[0];
                if($name != "fact_table" && $name != "dim_table") {
                    echo "<li>";
                    echo "<input name='selected-tables[]' id='$name' type='checkbox' class='' value='$name'>";
                    echo "<label for='$name'>$name</label>";
                    echo "</li>";
                }
            }
            echo "</ul>";
        }
        echo '<input type="submit" value="show star schema" name="submit">';


        echo "</form>";
    }


    
?>