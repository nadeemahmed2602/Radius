<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/transaction.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new transaction($db);
// get posted data
$data = json_decode(file_get_contents("php://input"));

$user->user_id = $data->user_id;
$stmt = $user->getData();
$itemCount = $stmt->rowCount();

if($itemCount > 0){
    
    $employeeArr = array();
    $employeeArr["transactions"] = array();

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        extract($row);
        $e = $row;
        array_push($employeeArr["transactions"], $e);
    }
    echo json_encode($employeeArr);
}else{
    http_response_code(404);
    echo json_encode(
        array("message" => "No record found.")
    );
}
?>