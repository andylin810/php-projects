<div class="left-container">
    <?php

        session_start();
        $_SESSION['left-db-button'] = 'select-fact-table';
        require 'components/left-container.php';
        // require 'db_conn.php';

        // $sql = "SHOW databases;";
        // $result = mysqli_query($conn, $sql);
        // $resultCheck = mysqli_num_rows($result);
        // if($resultCheck > 0){
        //     echo "<table>";
        //     while($row = mysqli_fetch_array($result)){
        //         $name = $row[0];
        //         echo "<tr>";
        //         echo "<td><button class='select-fact-table' value='$name' >{$name}</button></td>";
        //         echo "</tr>";

        //     }
        //     echo "</table>";
        // }

    ?>
</div>
<div class="right-container">


    <div class='table-form'></div>
    <div class='compare-table'></div>
    <div class="table-upload"></div>
    <div id="table">
        <?php
            if(isset($_GET['link'])) {
                if ($_GET['link'] == "success") {
                    echo "tables linked successfully";
                } else if ($_GET['link'] == "fail") {
                    echo "tables linked unsuccessful ";
                    if(isset($_GET['error'])) {
                        $err = $_GET['error'];
                        echo "error: $err";
                    }
                }
            }


        ?>
        
    </div>


</div>