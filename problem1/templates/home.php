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
                echo "<td><button class='database' value='$name' >{$name}</button></td>";
                echo "<td><button class='delete-button' value='$name'>delete</button><br></td>";
                echo "</tr>";

            }
            echo "</table>";
        }

    ?>
</div>
<div class="right-container">
    <div class="path"></div>
    <div class="table-form">
        <form action="add_table.php" method="post">
            <label for="">table name</label><input type="text" name="table_name">
            <input type="button" value="add row" class="addrow">
            <table id="add-table">
                <tr>
                    <th>field name</th>
                    <th>field type</th>
                    <th>length</th>
                    <th>primary key</th>
                    <th>null</th>
                </tr>
                <tr>
                    <td><input type="text" name="fields[0][field_name]"></td>
                    <td><select name="fields[0][type]">
                        <option value="int">INT</option>
                        <option value="varchar">VARCHAR</option>
                        <option value="char">CHAR</option>
                        </select>
                    </td>
                    <td><input type="number" name="fields[0][length]"></td>
                    <td><input type="checkbox" name="fields[0][pk]" value="primary key"></td>
                    <td><input type="checkbox" name="fields[0][null]" value="null"></td>
                </tr>
            </table>
            <input type="submit" name="submit" value="add table" >
        </form>
    </div>

    <!-- <div class="table-upload"></div> -->
    <div id="table"></div>

</div>