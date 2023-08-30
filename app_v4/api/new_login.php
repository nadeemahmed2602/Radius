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

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->phone = $data->phone;
$phoneExists = $user->phoneExists();


// check if email exists and if password is correct
if($phoneExists){
    $user->otp = mt_rand(1000, 9999);
    $user->Fast2SmsApiKey = $Fast2SmsApiKey;
    if($user->updateOTP()){
        $sendOTP = $user->sendOTPForgetPass();
        $userDetail = $user->getCustomerByPhone();
        http_response_code(200);
        echo json_encode(array("status" => "Success", "message" => "Otp sent success fully. use if for further process", "userDetail"=>$userDetail));
    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong while sending otp ! try again.")
        );
    } 
}else{
    http_response_code(404);
    echo json_encode(array("status" => "Failed", "message" => "No records found"));
}
?>