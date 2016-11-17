<?php
    include 'config/config.php';

    session_start();

    define('IS_ADMIN', true);
    define('IS_USER', true);


    // compare header with xsrf token to cookie-based session

//    if (isset($_SERVER['HTTP_AUTHORIZATION']) && isset($_SESSION['xsrfToken']) && $_SERVER['HTTP_AUTHORIZATION'] === $_SESSION['xsrfToken']) {
        $db = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($db->connect_errno) {
            http_response_code(500);
            if (ENV === 'local') {
                echo $db->connect_error;
            }
            die;
        }
//    }
//    else {
//        session_destroy();
//        http_response_code(401);
//        echo 'We restarted the server, or you logged out from other browser tab. Please reload the page.';
//        die;
//    }


    function col ($str) {
        global $db;
        return '`'.$db->real_escape_string($str).'`';
    }

    function val ($str) {
        global $db;
        return '"'.$db->real_escape_string($str).'"';
    }