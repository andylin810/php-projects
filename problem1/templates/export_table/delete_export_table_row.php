<?php

    /**
     * This file return a the row number in the export page, and
     * javascript will use it to delete the corresponding row, and 
     * it updates the number of row in the session variable.
     */

    session_start();
    if(isset($_SESSION['export-row'])) {
        if ($_SESSION['export-row'] > 1) {
            $_SESSION['export-row']--;
        }
        echo $_SESSION['export-row'];
    } else {
        echo "not set";
    }
    
