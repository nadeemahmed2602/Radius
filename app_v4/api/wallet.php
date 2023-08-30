<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// database connection will be here
// database connection will be here
session_start();
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/admin.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));

// set product property values
    $user->id = $data->user_id;


    $stmt = $user->getInfo();
    $itemCount = $stmt->rowCount();


//    echo json_encode($itemCount);

    if($itemCount > 0){
        
        $employeeArr = array();
//        $employeeArr = array();
//        $employeeArr["itemCount"] = $itemCount;
        $employeeArr["wallet"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($row);
            $e = $row;

            array_push($employeeArr["wallet"], $e);
        }
        echo json_encode($employeeArr);
    }

    else{
        http_response_code(404);
        echo json_encode(
            array("message" => "No record found.")
        );
    }
?>