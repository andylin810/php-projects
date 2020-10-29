<?php

    /**
     * This file return a the row number in the export page, and
     * javascript will use it to delete the corresponding row, and 
     * it updates the number of row in the session variable.
     */

    session_start();
    if(isset($_SESSION['import-row'])) {
        if ($_SESSION['import-row'] > 1) {
            $_SESSION['import-row']--;
        }
        echo $_SESSION['import-row'];
    } else {
        echo "not set";
    }
    
