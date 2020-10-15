<?php
    session_start();
    session_unset();
    require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles.css">
    <!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->
    <script src="jquery-3.5.1.js"></script>
    <script src="scripts.js"></script>
</head>
<body>

    <nav>
        <a href="/">home</a>
        <a href="/?page=compare">compare</a>
        <a href="/?page=star_schema">create star schema</a>
        <a href="/?page=show_relation">show star schema</a>
        <a href="/?page=export_table">export table</a>

    </nav>
    </nav>

    <div class="top-container">
        <label for="">database name</label><input id='database-name' type="text" name="database-name">
        <input type="submit" value="add database" class="add-database">

        <!-- <form id="upload-form" action="upload_table.php" method="post">
            <label for="">upload file:</label>
            <input type="file" name="upload-file" value="upload-file" class="upload-file">
            <input type="submit" name="submit" value="upload" class="upload-button">
        </form> -->
        
    </div>


    <div class="main-container">
        <!-- <div class="left-container">
            <?php

                // require 'db_conn.php';

                // $sql = "SHOW databases;";
                // $result = mysqli_query($conn, $sql);
                // $resultCheck = mysqli_num_rows($result);
                // if($resultCheck > 0){
                //     echo "<table>";
                //     while($row = mysqli_fetch_array($result)){
                //         $name = $row[0];
                //         echo "<tr>";
                //         echo "<td><button class='database' value='$name' >{$name}</button></td>";
                //         echo "<td><button class='delete-button' value='$name'>delete</button><br></td>";
                //         echo "</tr>";

                //     }
                //     echo "</table>";
                // }

            ?>
        </div> -->

        <?php
        get_page_content();
        ?>
        <!-- <div class="right-container">
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

            <?php
                // if ($_POST["submit"]) {
                //     if (!empty($_POST["field_name"])) {
                //         require "load_table.php";
                //     }
                //     else {
                //         echo "Location: index.php?error=emptyfield";
                //     }
                // }
            ?>
            <div class="table-upload"></div>
            <div id="table">
            <?php
                // if ($_POST["submit"]) {
                //     if (!empty($_POST["field_name"])) {
                //         require "load_table.php";
                //     }
                //     else {
                //         echo "Location: index.php?error=emptyfield";
                //     }
                // }
                if (isset($_REQUEST['success'])) {
                    echo "success query!";
                }

                if (isset($_REQUEST['error'])) {
                    if($_REQUEST['error'] == 'missDB'){
                        echo "please select a database";
                    } else if ($_REQUEST['error'] =='invalidQuery'){
                        echo "invalid query";
                    }

                }
            ?>
                
            </div>

        </div> -->

    




    </div>

    
</body>
</html>