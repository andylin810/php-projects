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

    <nav class="nav-bar">
        <a 
            <?php 
            if(!isset($_GET['page']) || $_GET['page']=='home' ) {
                echo "class='active'";
            }
            ?> 
        href="/">HOME</a>
        <a 
            <?php 
                if($_GET['page']=='compare' ) {
                    echo "class='active'";
                }
            ?> 
            href="/?page=compare">COMPARE</a>
        <a         
            <?php 
                if($_GET['page']=='star_schema' ) {
                    echo "class='active'";
                }
            ?> 
            href="/?page=star_schema">CREATE STAR SCHEMA</a>
        <a 
            <?php 
            if($_GET['page']=='show_relation' ) {
                echo "class='active'";
            }
            ?> 
            href="/?page=show_relation">SHOW STAR SCHEMA</a>
        <a 
            <?php 
            if($_GET['page']=='export_table' ) {
                echo "class='active'";
            }
            ?> 
            href="/?page=export_table">EXPORT TABLE</a>

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

        <?php
        get_page_content();
        ?>
            <div class="table-upload"></div>
            <div id="table">
            <?php
                // if (isset($_REQUEST['success'])) {
                //     echo "success query!";
                // }

                // if (isset($_REQUEST['error'])) {
                //     if($_REQUEST['error'] == 'missDB'){
                //         echo "please select a database";
                //     } else if ($_REQUEST['error'] =='invalidQuery'){
                //         echo "invalid query";
                //     }

                // }
            ?>
            </div>

        

    




    </div>

    
</body>
</html>