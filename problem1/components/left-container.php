<?php

    session_start();
    require 'db_conn.php';
    $buttonClass = $_SESSION['left-db-button'];

    $sql = "SHOW databases;";
    $result = mysqli_query($conn, $sql);
    $resultCheck = mysqli_num_rows($result);
    if($resultCheck > 0){
        echo "<table>";
        while($row = mysqli_fetch_array($result)){
            $name = $row[0];
            echo "<tr>";
            echo "<td><button class='$buttonClass' value='$name' >{$name}</button></td>";
            if($buttonClass == 'database')
                echo "<td><button class='delete-button' value='$name'>delete</button><br></td>";
            echo "</tr>";

        }
        echo "</table>";
    }