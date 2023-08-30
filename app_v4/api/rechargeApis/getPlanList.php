<?php
ini_set("display_errors", 1);
    ini_set("track_errors", 1);
    ini_set("html_errors", 1);
    error_reporting(E_ALL);
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/wallet.php';
// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user ->operatorCode = $data->operatorCode;

$curl = curl_init();
$url = "https://myrc.in/plan_api/mobile_normal_plan?username=503142&token=9de2e6e3de7d7ea54e6c67c0d6976b22&opcode=".$data->operatorCode;

curl_setopt_array($curl, array(
  CURLOPT_URL =>$url ,
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$r = json_decode($response, true);
$err = curl_error($curl);
echo $response;
// $status = $r['status'];
// if($status == 'Failure'){
//      http_response_code(404);
//         echo json_encode(
//             array("status" => "Failed", "message" => "Something is wrong please try again.")
//         );
// }else{
//     $user->updated_balance = $WalletAmout - $rechargeAmount;
//     if($user->UpdateWallet()){
//         http_response_code(200);
//         echo json_encode(
//             array("status" => "Success", "message" => "Recharge of ".$rechargeAmount." completed on mobile no ".$data->number)
//         );
//     }else{
//          http_response_code(404);
//          echo json_encode(
//             array("status" => "Failed", "message" => "Something is wrong please try again.")
//         );
//     }
    
// }
curl_close($curl);


?>