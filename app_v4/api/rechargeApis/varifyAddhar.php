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
include_once("../connection.php");

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));
$aadharNo = $data->aadharNo;
$ref_id = $data->ref_id;
$userId = $data->userId;
$otp = $data->otp;
$curl = curl_init();
// $url = "https://connect.inspay.in/v3/verification/get_aadhaar_otp?username=IP9524853259&token=e6c4df4435e769044bfc21cd0b33e52f&aadhaar_no=".$aadharNo;
$url = "https://www.connect.inspay.in/v3/verification/api?username=IP9524853259&token=e6c4df4435e769044bfc21cd0b33e52f&opcode=AKYC&number=".$aadharNo."&ref_id=".$ref_id."&otp=".$otp."&orderid=0&format=json";
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
curl_close($curl);
// echo $response;
$status = $r['status'];
if($status == 'Success'){
  $q_checkDocumentNumberExist = "SELECT * from customer where documentNumber='$aadharNo'";
  $result_checkDocumentNumberExist = mysqli_query($con, $q_checkDocumentNumberExist);
  $records=mysqli_num_rows($result_checkDocumentNumberExist);
  if($records <= 0){
    $query = "UPDATE customer SET accountVetified = '1', varificationType = 'a', documentName='Aadhar Card', documentNumber = '$aadharNo' where id = '$userId'";
    $result2 = mysqli_query($con, $query);
    if($result2 > 0){
      http_response_code(200);
      echo json_encode(array("status" => "Success", "message" => "KYC Verification Complete.") );
    }else{
      http_response_code(404);
      echo json_encode(array("status" => "Failed", "message" => "Error while verifying otp.") );
    }
  }else{
    echo json_encode(
      array(
        "status" => "Failed",
        "message" => "This document is already used ! try with different document number"));
  }

}else{
  $message = $r['message'];
  http_response_code(404);
  echo json_encode(
      array("status" => "Failed", "message" => $message)
  );

}
?>