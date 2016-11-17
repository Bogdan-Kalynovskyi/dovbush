<?php


if (PHP_VERSION_ID < 50500) {
    echo "Minimal required version of PHP is 5.5";
    die;
}


define('ENV', 'local');


error_reporting(E_ALL ^ E_DEPRECATED);


/***************************************************************
**    TODO: uncomment line below in production version!    **/
//    error_reporting(0);


/**    TODO: delete api/install.php file after installation   **/



$google_api_id = '211499477101-d78crq8gs6sojr7grdlm9ebmoltiel71.apps.googleusercontent.com';



$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dovbush';

$sub_domain_depth = 1;


//
//UPDATE route AS r
//INNER JOIN routemaker_route AS rr ON r.id = rr.route_id
//SET r.routemaker_id = rr.routemaker_id