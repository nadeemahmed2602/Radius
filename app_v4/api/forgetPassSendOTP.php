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

# Get Posted Data
$data = json_decode(file_get_contents("php://input"));

# New Code Start
// $user->name = $data->name;
// $user->email = $data->email;
$user->phone = $data->phone;
// $user->password = $data->password;
$user->reg_date = date('Y-m-d');
$user->parent_id = 0;
if(isset($data->parent_id) && $data->parent_id != ""){
    $user->parent_id=$data->parent_id;
}
$parent_id = $user->parent_id;

$customerData = $user->getCustomerByPhone();

if($customerData){
    if(!$user->checkIsPhoneVerified()){
      
        echo json_encode(
            array("status" => "Failed", "message" => "Please check your mobile is not registered")
        );
    }else{
        $user->otp = mt_rand(1000, 9999);
        $user->Fast2SmsApiKey = $Fast2SmsApiKey;
        if($user->updateOTP()){
            $sendOTP = $user->sendOTPForgetPass();
            $user->getCustomerByPhone();
            $user_data = array(
                "id"    => $user->id,
                "name" => $user->name,
                "email" => $user->email,
                "phone" => $user->phone,
                "wallet_amount" => $user->wallet_amount
            );
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "Otp sent success fully.", 'user'=>$user_data));
        }else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong please try again.")
            );
        }
    }
}else{
    $user->otp = mt_rand(1000, 9999);
    $user->Fast2SmsApiKey = $Fast2SmsApiKey;
    if($user->create()){
        $sendOTP = $user->sendOTPForgetPass();
        $user->getCustomerByPhone();
          $user_data = array(
            "id"    => $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "wallet_amount" => $user->wallet_amount,
            "parent_id"=>$parent_id
        );
        http_response_code(200);
        echo json_encode(array("status" => "Success", "message" => "Otp sent success fully.", 'user'=>$user_data));
    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong please try again.")
        );
    }
}
?>