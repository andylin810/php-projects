<?php

    generateData(500,5);

    function generateData($maxRow,$maxCol) {
        $fh = fopen('random_data.txt', 'w');


        $row = 1;
        while ($row <= $maxRow) {
            $col = 1;
            fwrite($fh, $row);
            fwrite($fh, ",");
            while ($col <= $maxCol) {
                fwrite($fh, generateRandomString());
                if ($col != $maxCol)
                    fwrite($fh, ",");
                $col++;
            }
            if ($item != $last)
                fwrite($fh, ",");
            if ($row != $maxRow)
                fwrite($fh, "\n");
            $row++;
        }
    
        fclose($fh);
    }



    function generateRandomString($length = 5) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    header("Location: /");
?>