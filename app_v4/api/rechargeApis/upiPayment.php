<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
    error_reporting(E_ALL);
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once '../config/database.php';
include_once '../objects/wallet.php';
include_once '../objects/transaction.php';
include_once '../config/core.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);
$transaction = new transaction($db);
$booking = new transaction($db);


// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user ->user_id = $data->user_id;
$user ->amount = $data->amount;
$user ->number = $data->number;
$user ->phone = $data->phone;
$user ->sender_no = $data->sender_no;
$user ->receiver_no = $data->receiver_no;

$stmt = $user->getWalletBalance();
$itemCount = $stmt->rowCount();
$rechargeAmount = $data->amount;
$amount = $data->amount;
$WalletAmout = 0;
$utilityWallet = 0;
$investment_wallet = 0;
$earnings_wallet = 0;
$usdt_wallet = 0;
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $WalletAmout = $row['wallet_amount'];
            $utilityWallet = $row['utilityWallet'];
            $investment_wallet = $row['investment_wallet'];
            $earnings_wallet = $row['earnings_wallet'];
            $usdt_wallet = $row['usdt_wallet'];

        }
    }
    
$intwalletAmount = (double) $utilityWallet;
$intamount = (double) $amount;

// echo "intwalletAmount".$intwalletAmount;
// echo " | intamount".$intamount;

if($intwalletAmount >= $intamount){
    $curl = curl_init();
$url = "https://www.connect.inspay.in/v3/payout/api?username=IP9524853259&token=e6c4df4435e769044bfc21cd0b33e52f&opcode=UPI&phone=".$data->phone."&name=VRUSHALI%20BHARATBHAI%20SONI&email=info@test.in&number=".$data->number."&amount=".$intamount."&orderid=100000000114&format=json";
// $url = "https://www.connect.inspay.in/v3/recharge/api?username=IP9524853259&token=e6c4df4435e769044bfc21cd0b33e52f&opcode=".$user->opcode."&number=".$user->number."&amount=".$user->amount."&orderid=RRR&format=json";

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
$status = $r['status'];

if($status == 'Failure'){
     http_response_code(404);
     echo $response;
        // echo json_encode(
        //     array("status" => "Failed", "message" => "Something is wrong please try again.")
        // );
}else{
    $rechargeAmount = $rechargeAmount/82;
    $var_updated_balance_utility = $utilityWallet - $rechargeAmount;
    $user->updated_balance_utility = $var_updated_balance_utility;

      //set up transaction details
      $transaction->user_id = $data->user_id;
      $transaction->amount = $rechargeAmount;
      $transaction->status = $debit;
      $transaction->transaction_type = $SCAN_AND_PAY;
      $transaction->affected_wallet = $utility_wallet;
      $transaction->payment_for = $SCAN_AND_PAY;
      $transaction->mobile_number = $user->number;
      $transaction->consumer_number = NULL;
      $transaction->operator_code = "UPI";
      $transaction->flight_no = NULL;
      $transaction->wallet_balance = $WalletAmout;
      $transaction->utility_balance = $var_updated_balance_utility;
      $transaction->investment_balance = $investment_wallet;
      $transaction->earning_balance = $earnings_wallet;
      $transaction->usdt_wallet = $usdt_wallet;

        // add booking
      $booking->type = $scan_and_pay;
      $booking->amount = $rechargeAmount;
      $booking->associatenumber = $user->number;
      $booking->status = $success;
      $booking->user_id = $data->user_id;
      $booking->sender_no = $data->sender_no;
      $booking->receiver_no = $data->receiver_no;
      
    if($user->UpdateOnlyUtilityWallet()){
       $transaction->create();
       $booking->createScanPayHistory();

        http_response_code(200);
        echo json_encode(
            array("status" => "Success", "message" => "UPI Payment of ".$data->amount." completed ")
        );
    }else{
         http_response_code(404);
          echo $response;
    }
    
}
curl_close($curl);
}else{
    http_response_code(404);
    echo json_encode(
        array("status" => "Failure", "message" => "Insufficient utility wallet balance. Please deposit USD in your utility wallet.")
    );
}




?>