<?php

    /**
     * This script load all tables from selected database then displaying them
     * in the form of buttons.
     */ 
    require '../../db_conn.php';

    session_start();

    if(isset($_POST['button_name'])) {
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    // Execute query to show tables then fetch the results and display them as HTML table
    if(isset($_SESSION['database'])) {
        mysqli_select_db($conn, $_SESSION['database']);
        $sql = "show tables;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            while($row = mysqli_fetch_array($result)){
                $name = $row[0];
                echo "<div class='table-row'>";                
                echo "<button class='show-table table-button' value='$name' data-loaded='no'>$name</button>";
                echo "<button class= 'delete-table table-button' value='$name'>Drop Table</button>";
                echo "<button class= 'modify-table table-button' value='$name'>Save Changes</button>";
    
                echo "<table class='nice-table' id='$name'></table>";
                echo "</div>";     
    
            }
        }
    } else {
        echo "db not set";
    }

        


?>