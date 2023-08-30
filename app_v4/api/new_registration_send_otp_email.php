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
$user->email = $data->email;
$user->phone = "";
$user->countryCode = "";
$user->password = "";
$user->parent_id = 0;
if(isset($data->parent_id) && $data->parent_id != ""){
    $user->parent_id=$data->parent_id;
}
$parent_id = $user->parent_id;
$customerData = $user->getCustomerByEmail();
$email_exists = $user->emailExists();

    if($email_exists){
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Email is Already registered.")
        );
    }else{
        $user->otp = mt_rand(1000, 9999);
        $connew=mysqli_connect("localhost","experted_radius2","experted_radius2","experted_radius") or die("can't connect");
        $query = "INSERT INTO `customer` ( `email`, `otp`, `parent_id`) VALUES ('$user->email', '$user->otp','$parent_id');";
        $result2 = mysqli_query($connew, $query);
        $child_user_id = mysqli_insert_id($connew);
        if($parent_id > 0){
            $query_referal = "INSERT INTO `referral` (`id`, `parent_id`, `child_id`) VALUES (NULL, '$parent_id', '$child_user_id');";
            $result3 = mysqli_query($connew, $query_referal);
        }
        if($result2>0){
            $sendOTP = $user->sendOTPOnMail();
            $user->getCustomerByEmail();
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