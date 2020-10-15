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
                echo "<td><button class='select-database' value='$name' >{$name}</button></td>";
                echo "<td><button class='delete-button' value='$name'>delete</button><br></td>";
                echo "</tr>";

            }
            echo "</table>";
        }

    ?>
</div>
<div class="right-container">


    <div class='table-form'>
        <!-- old upload form -->
        <!-- <form id="upload-form" action="upload_table.php" method="post">
            <label for="">upload file:</label>
            <input type="file" name="upload-file" value="upload-file" class="upload-file">
            <input type="submit" name="submit" value="upload" class="upload-button">
        </form> -->
        <form id='save-upload-form' enctype="multipart/form-data" action='quick_save_upload_table.php' method='post'>
            <label for="">upload file:</label>
            <input type="file" name="upload-file" value="upload-file" class="upload-file">
            <input type="button" value="upload" class="upload-button">
            <div class="set-table-field"></div>
        </form>
    </div>
    <div class='compare-table'></div>
    <form action="generate_file.php" method="get">
        <input style="display: flex;" type="submit" value="generate file">
    </form>

    <div class="table-upload"></div>
    <div id="table"></div>


</div>