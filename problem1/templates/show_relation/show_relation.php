<div class="left-container">
    <?php


        session_start();
        $_SESSION['left-db-button'] = 'select-dim-tables';
        require 'components/left-container.php';

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