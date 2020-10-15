<?php

    session_start();
    if(isset($_SESSION['export-row'])) {
        if ($_SESSION['export-row'] > 1) {
            $_SESSION['export-row']--;
        }
        echo $_SESSION['export-row'];
    } else {
        echo "not set";
    }
    
