<?php

//  ini_set("display_errors", 1);
//     ini_set("track_errors", 1);
//     ini_set("html_errors", 1);
//     error_reporting(E_ALL);
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
$user->name = "";
$user->email = "";
$user->phone = $data->phone;
$user->countryCode = $data->countryCode;
$user->password = "";
$user->parent_id = 0;
if(isset($data->parent_id) && $data->parent_id != ""){
    $user->parent_id=$data->parent_id;
}
$parent_id = $user->parent_id;
$customerData = $user->getCustomerByPhone();
$phone_exists = $user->phoneExists();

    if($phone_exists){
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Mobile Number is Already registered.")
        );
    }else{
        $user->otp = mt_rand(1000, 9999);
        $user->Fast2SmsApiKey = $Fast2SmsApiKey;
        $connew=mysqli_connect("localhost","experted_radius2","experted_radius2","experted_radius") or die("can't connect");
        $query = "INSERT INTO `customer` (`contryCode`, `phone`, `otp`, `parent_id`) VALUES ('$user->countryCode','$user->phone', '$user->otp','$parent_id');";
        $result2 = mysqli_query($connew, $query);
       
        if($result2>0){
            $sendOTP = $user->sendOTP();
            $user->getCustomerByPhone();
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "You are registered successfilly, please verify your account to login."));
        }else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong please try again.")
            );
        }
    }

?>