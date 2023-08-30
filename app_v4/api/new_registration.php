<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

session_start();
include_once 'config/database.php';
include_once 'objects/admin.php';
include_once 'config/core.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

# Get Posted Data
$data = json_decode(file_get_contents("php://input"));
$user->phone = $data->phone;
$user->reg_date = date('Y-m-d');
$customerData = $user->getCustomerByPhone();
if($customerData){
        $user->otp = mt_rand(1000, 9999);
        $user->Fast2SmsApiKey = $Fast2SmsApiKey;
        if($user->updateOTP()){
            $sendOTP = $user->sendOTPForgetPass();
            $userDetail = $user->getCustomerByPhone();
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "Otp sent success fully.", "userDetail"=>$userDetail));
        }else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong while sending otp ! try again.")
            );
        }
}
?>