<div class="left-container">
    <?php

        session_start();
        $_SESSION['left-db-button'] = 'database';
        require 'components/left-container.php';


    ?>
</div>
<div class="right-container">
    <div class="path"></div>
    <div class="table-form">
        <form action="templates/home/add_table.php" method="post">
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