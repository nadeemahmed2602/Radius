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
$user_verify_old_mpin = new User($db);
$user_change_mpin = new User($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->newmpin = $data->newmpin;
$user->oldmpin = $data->oldmpin;
$user->confirmMpin = $data->confirmMpin;

$user_change_mpin->mpin = $data->newmpin;
$user_change_mpin->id = $data->user_id;

$user_verify_old_mpin->mpin = $data->oldmpin;
$user_verify_old_mpin->id = $data->user_id;
// check if email exists and if password is correct
if($user_verify_old_mpin->verifyOldMpin()){
    if($data->newmpin == $data->confirmMpin){
        if($user_change_mpin->changeMpin()){
        
            http_response_code(200);
            
            echo json_encode(array("status" => "Success", "message" => "Mpin changed Successfully.") );
            
        } else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong please try again.")
            );     
        } 
    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "New and confirm Mpin must be same")
        );
    }
    
    
}

else{

    http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Old Mpin is wrong please try again.")
        );
}
?>