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
$email = $data->user;
$user->email = $email;
$otp = rand(10000,999999);
$user->otp = $otp;

$email_exists = $user->emailExists();


// check if email exists and if password is correct
if($email_exists){
  
    
    
    $fileatt = "$name " . "_".$traveller. "_invoice.pdf"; // Path to the file
    $fileatt_type = "application/pdf"; // File Type
    $fileatt_name = ""; // Filename that will be used for the file as the attachment

    $email_from = "info@purie.in"; // Who the email is from
    $email_subject = "Forget Password"; // The Subject of the email
    $email_message .= 'here is your otp '.$otp;
                                                                
 
 
    
    $email_to = $email; // Who the email is to

    $headers = "From: Purie.in ".$email_from;

    $file = fopen($fileatt,'rb');
    $data = fread($file,filesize($fileatt));
    fclose($file);

    $semi_rand = md5(time());
    $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

    $headers .= "\nMIME-Version: 1.0\n" .
    "Content-Type: multipart/mixed;\n" .
    " boundary=\"{$mime_boundary}\"";

    $email_message .= "This is a multi-part message in MIME format.\n\n" .
    "--{$mime_boundary}\n" .
    "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
    "Content-Transfer-Encoding: 7bit\n\n" .
    $email_message .= "\n\n";

    $data = chunk_split(base64_encode($data));

   

    $ok = @mail($email_to, $email_subject, $email_message, $headers);

    if($ok)
    
    {
        if($user->updateInfo()){
        
        http_response_code(200);
        
        echo json_encode(array("status" => "Success", "message" => "Otp sent to your email id") );
        
    } else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong please try again.")
        );
        
    }
    } 



else {
    http_response_code(404);
        
        echo json_encode(array("status" => "Failed", "message" => "Something is wrong please try again.") );
    }
    
    
    
   
}

else{

    http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Your Email id not registerd with us.")
        );
}
?>