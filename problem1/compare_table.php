<?php
    require 'db_conn.php';

    session_start();
    if(isset($_POST['button_name'])){
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    if(isset($_SESSION['database'])){
        $db = $_SESSION['database'];
        mysqli_select_db($conn, $db);
        $sql = "show tables;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){

            echo "<form id='compare-table-form' action='show_table_difference.php' method='post'>";
            for($i = 1; $i < 3; $i++) {
                echo "table$i:";
                echo "<select name='table$i'>";
                while($row = mysqli_fetch_array($result)){
                    $name = $row[0];
                    echo "<option value='$name'>$name</option>";
                }
                echo  "</select>";
                mysqli_data_seek($result,0);
            } 
            echo "<input name='submit' type='submit' value='compare'>";
            echo "</form>";
        }

    } else {
        echo "something wrong";
    }
        


?>