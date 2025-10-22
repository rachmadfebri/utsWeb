<?php
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'db_web_portofolio');
define('DB_USER', 'postgres');
define('DB_PASS', ''); 

$conn_string = "host=" . DB_HOST . " port=" . DB_PORT . " dbname=" . DB_NAME . " user=" . DB_USER . " password=" . DB_PASS;

function json_response($data, $status_code = 200) {
    http_response_code($status_code);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}
?>
