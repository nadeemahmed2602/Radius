<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
ini_set("display_errors", 1);
    ini_set("track_errors", 1);
    ini_set("html_errors", 1);
    error_reporting(E_ALL);
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
$user->email = $data->email;
$emailExists = $user->emailExists();


// check if email exists and if password is correct
if($emailExists){
    $user->otp = mt_rand(1000, 9999);
    if($user->updateOTP()){
        $sendOTP = $user->sendOTPForgetPassEmail();
        $userDetail = $user->getCustomerByEmail();
        http_response_code(200);
        echo json_encode(array("status" => "Success", "message" => "Otp sent success fully.", "userDetail"=>$userDetail));
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