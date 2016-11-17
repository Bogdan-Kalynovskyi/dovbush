<?php

include 'php/auth.php';
include 'php/utils.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,PUT,POST,PATCH,DELETE');


$request = $_SERVER['REQUEST_URI'];

$parts = explode('/', $request);
$table = $parts[$sub_domain_depth + 2];


$filter = '';

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        can_read_from_table($table);
        $filter = filter_from_request($parts);

        $result = $db->query('SELECT * FROM '.$table.$filter . ' LIMIT 100');
        switch ($result->num_rows) {
            case 0:
                break;
            case 1:
                echo json_encode($result->fetch_assoc());
                break;
            default:
                echo "[\n";
                while ($line = $result->fetch_assoc()) {
                    echo json_encode($line) . ",\n";
                };
                echo ']';
        }
        break;

    case 'POST':
        can_write_to_table($table);

        $post = decode_post();
        $insert = [];
        foreach ($post as $key => $value) {
            array_push($insert, key($key) . '=' . val($value));
        }
        $insert = ' SET ' . implode(', ', $insert);
        $db->query('INSERT INTO ' . $table . $insert);
        break;

    case 'PUT':
        $filter = filter_from_request($parts);
        can_write_to_table($table);
        is_owner($table, $filter);

        $post = json_decode(file_get_contents('php://input'), true);
        $insert = [];
        foreach ($post as $key => $value) {
            array_push($insert, key($key) . '=' . val($value));
        }
        $insert = ' SET ' . implode(', ', $insert);
        $db->query('UPDATE ' . $table . $insert . $filter);
        break;

    case 'PATCH':
        break;

    case 'DELETE':
        $filter = filter_from_request($parts);
        can_write_to_table($table);
        is_owner($table, $filter);

        $db->query('DELETE FROM ' . $table . $filter);
        break;
}

