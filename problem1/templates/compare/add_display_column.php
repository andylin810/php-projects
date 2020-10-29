<?php
    session_start();
    if(isset($_SESSION['import-row']))
        echo $_SESSION['import-row'];
