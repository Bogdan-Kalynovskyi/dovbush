<?php
    include "../config/config.php";

    session_start();


    // ask Google for verification

    if (isset($_GET['authToken'])) {
        $url = 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . urlencode($_GET['authToken']);
        $json = @file_get_contents($url);
        $obj = @json_decode($json);
        if ($obj && isset($obj->aud) && $obj->aud === $google_api_id) {
            $_SESSION['userGoogleId'] = $obj->sub;
            $_SESSION['xsrfToken'] = base64_encode(openssl_random_pseudo_bytes(32));
            echo $_SESSION['xsrfToken'];
        }
        else {
            http_response_code(401);
            echo 'Google authentication failed';
        }
    }


    // prevent api from recognising this client

    else {
        $post = json_decode(file_get_contents('php://input'), true);
        if (isset($post['logout']) && $post['logout'] === $_SESSION['xsrfToken']) {
            session_destroy();
        }
    }
