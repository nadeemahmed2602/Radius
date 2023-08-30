<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config/database.php';
include_once 'objects/wallet.php';
include_once 'objects/transaction.php';
include_once 'config/core.php';


// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);
$transaction = new transaction($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user ->user_id = $data->user_id;
$user ->amount = $data->amount;
$cAmount = $data->amount;

$stmt = $user->getWalletBalance();
$itemCount = $stmt->rowCount();
$amout = 0;
$utilityWallet = 0;
$investment_wallet = 0;
$earnings_wallet = 0;
$usdt_wallet = 0;
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $amout = $row['wallet_amount'];
            $utilityWallet = $row['utilityWallet'];
            $investment_wallet = $row['investment_wallet'];
            $earnings_wallet = $row['earnings_wallet'];
            $usdt_wallet = $row['usdt_wallet'];
        }
    }

    $wallet_blns = $amout-$cAmount;
    $user ->updated_balance = $wallet_blns;

    //set up transaction details
    $transaction->user_id = $data->user_id;
    $transaction->amount = $cAmount;
    $transaction->status = $debit;
    $transaction->transaction_type = $wallet_withdraw;
    $transaction->affected_wallet = $wallet;
    $transaction->payment_for = $wallet_withdraw;
    $transaction->mobile_number = NULL;
    $transaction->consumer_number = NULL;
    $transaction->operator_code = NULL;
    $transaction->flight_no = NULL;
    $transaction->wallet_balance = $wallet_blns;
    $transaction->utility_balance = $utilityWallet;
    $transaction->investment_balance = $investment_wallet;
    $transaction->earning_balance = $earnings_wallet;
    $transaction->usdt_wallet = $usdt_wallet;

    $user->UpdateWallet();
    if($transaction->create()){
        
        http_response_code(200);
        
        echo json_encode(array("status" => "Success", "message" => "Withdrawal done.") );
        
    } else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong please try again.")
        );
    }

    
    

?>