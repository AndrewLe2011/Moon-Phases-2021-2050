<?php
    switch (@parse_url($_SERVER['REQUEST_URI'])['path']) {
        case '/main.php':
            require 'main.php';
            break;
        default:
            require 'main.php';
    }
?>