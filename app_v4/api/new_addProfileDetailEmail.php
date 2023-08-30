<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// database connection will be here
// database connection will be here
session_start();
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/admin.php';

include_once 'config/core.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// check email existence here

# Get Posted Data
$data = json_decode(file_get_contents("php://input"));

# New Code Start
$user->name = $data->name;
$user->email = $data->email;
$user->phone = $data->phone;
$user->password = "";
$user->reg_date = date('Y-m-d');

$customerData = $user->getCustomerByEmail();
$user->name = $data->name;
$user->email = $data->email;
$user->phone = $data->phone;
$user->password = "";
$user->reg_date = date('Y-m-d');
$customerDataByPhone = $user->getCustomerByPhone();
if($customerData){
    if($customerDataByPhone){
        echo json_encode( array("status" => "Failed", "message" => "Mobile number is already used, try with new one"));
    }else{
        if($user->updateUserInfoEmail($data->name,$data->email,$data->phone)){
            $user->getCustomerDataByEmail();
            $user_data = array(
                "id"    => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "wallet_amount" => $user->wallet_amount
            );
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "You are registered successfilly, please generate Mpin to login.", 'user'=>$user_data));
        }else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong please try again.")
            );
        }
    }
}else{
 http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Record not found")
            );
}
    
?>