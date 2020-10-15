<?php


    function get_page_content(){
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';
        $path = getcwd() . '/templates' . "/$page" . '.php';

        if (! file_exists($path)) {
            $path = getcwd() . '/templates' . '/404.php';
        }

        // echo file_get_contents($path);
        return require $path;
    }