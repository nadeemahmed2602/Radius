<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here
// database connection will be here
session_start();

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
// echo($data->base64data);

date_default_timezone_set('Asia/Kolkata');
$logDateTime = date('Y-m-d H:i:s', time());

file_put_contents('logs/logs2.txt', "\n" .$data->base64data, FILE_APPEND);
http_response_code(202);
        echo json_encode(
            array("status" => "Success")
        );

?>