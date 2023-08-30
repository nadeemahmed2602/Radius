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

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set product property values
$user->phone = $data->email;
$user->token = $data->token;
$phoneExists = $user->phoneExists();


// check if email exists and if password is correct
if($phoneExists && password_verify($data->password, $user->password)){
    
    if($user->updateToken()){
         $user_data = array(
             "id" =>  $user->id,
            "name" => $user->name,
            "email" => $user->email,
            "phone" => $user->phone,
            "wallet_amount" => $user->wallet_amount
        );
        http_response_code(200);
        
        echo json_encode(array("status" => "Success", "message" => "Login Success.", "user"=>$user_data) );
    }
    
    
   
}

else{

    http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Id & Password is wrong please try again.")
        );
}
?>