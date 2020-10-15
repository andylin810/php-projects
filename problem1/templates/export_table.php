<div class="left-container">
    <?php

        require 'db_conn.php';

        $sql = "SHOW databases;";
        $result = mysqli_query($conn, $sql);
        $resultCheck = mysqli_num_rows($result);
        if($resultCheck > 0){
            echo "<table>";
            while($row = mysqli_fetch_array($result)){
                $name = $row[0];
                echo "<tr>";
                echo "<td><button class='select-export-tables' value='$name' >{$name}</button></td>";
                echo "</tr>";

            }
            echo "</table>";
        }

    ?>
</div>
<div class="right-container">


    <div class='table-form'></div>
    <div class='compare-table'></div>
    <div class="table-upload"></div>
    <div id="table">
        <div class='table-left'></div>
        <div class='table-right'></div>
    </div>


</div>