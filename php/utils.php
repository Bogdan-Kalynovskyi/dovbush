<?php

    function bad_request () {
        http_response_code(400);
        die;
    }


    function decode_post () {
        $post = @json_decode(file_get_contents('php://input'), true);
        if (!$post) {
            bad_request();
        }
        return $post;
    }


    function filter_from_request ($parts) {
        global $sub_domain_depth;
        $filter_part = $parts[$sub_domain_depth + 4];

        switch ($parts[$sub_domain_depth + 3]) {
            case 'id':
                if (is_int($filter_part)) {
                    return ' WHERE id='.$filter_part;
                }
                break;

            case 'limit':
                if (is_int($filter_part)) {
                    return ' LIMIT ' . $filter_part;
                }
                break;

            case 'page':
                $filter_part = explode(',', $filter_part);
                if (is_int($filter_part[0]) && is_int($filter_part[1])) {
                    return ' LIMIT ' . $filter_part[0] * $filter_part[1] . ', ' . $filter_part[1];
                }
                break;
        }

        bad_request();
        return '';
    }


    function can_read_from_table ($table) {
        $allowed_read = ['news', 'users', 'routes', 'routmakers', 'regions'];
        if (array_search($table, $allowed_read) === false) {
        //    http_response_code(403);
            die;
        }

        return true;
    }


    function can_write_to_table ($table) {
        if (!IS_USER) {
            http_response_code(401);
            die;
        }

        $allowed_write = ['news', 'users', 'routes', 'routmakers', 'regions'];
        if (array_search($table, $allowed_write) === false) {
            http_response_code(403);
            die;
        }

        return true;
    }


    function is_owner ($table, $filter) {
        global $db;

        $result = $db->query('SELECT * FROM '.$table.$filter . ' LIMIT 1');
        if (!$result[0] || !(($table === 'user' && $_SESSION['user_id'] === $result[0]['id']) || $_SESSION['user_id'] === $result[0]['owner'])) {
            http_response_code(403);
            die;
        }

        return true;
    }