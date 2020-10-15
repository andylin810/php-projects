<?php
    require 'db_conn.php';

    session_start();
    // session_unset();   //deletes session data
    // session_destroy(); //deletes session 

    // if(!isset($_SESSION['database'])) {
    
    //     $button = $_POST['button_name'];

    //     if ($button) {
    //         mysqli_select_db($conn, $button);
    //     } else {
    //         mysqli_select_db($conn, "addend");

    //     }
    //     $_SESSION['database'] = $button;
    // } else {
    //     mysqli_select_db($conn, $_SESSION['database']);
    // }

    if(isset($_POST['button_name'])) {
        $button = $_POST['button_name'];
        $_SESSION['database'] = $button;
    }

    if(isset($_SESSION['database'])) {
        mysqli_select_db($conn, $_SESSION['database']);
        $sql = "show tables;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            while($row = mysqli_fetch_array($result)){
                $name = $row[0];
                echo "<button class='show-table' value='$name' data-loaded='no'>$name</button>";
                echo "<button class= 'delete-table' value='$name'>Drop Table</button>";
                echo "<button class= 'modify-table' value='$name'>Save Changes</button><br>";
    
                echo "<table class='nice-table' id='$name'>";
                echo "</table>";
    
            }
        }
    } else {
        echo "db not set";
    }


    //$result = mysqli_multi_query($mysqli, $query);   -multiple query

    //mysqli_select_db($conn, $button);               -select databse to connect to

    //$sql = "select * from user;";


        


?>