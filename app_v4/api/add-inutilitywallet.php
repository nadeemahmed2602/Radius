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
$transaction_wallet = new transaction($db);
$transaction_utility_wallet = new transaction($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user ->user_id = $data->user_id;
$user ->amount = $data->amount;
$amount = $data->amount;
$stmt = $user->getWalletBalance();
$itemCount = $stmt->rowCount();
$walletAmount = 0;
$utilityWalletAmount = 0;
$investment_wallet = 0;
$earnings_wallet = 0;
$usdt_wallet = 0;
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $walletAmount = $row['wallet_amount'];
            $utilityWalletAmount = $row['utilityWallet'];
            $investment_wallet = $row['investment_wallet'];
            $earnings_wallet = $row['earnings_wallet'];
            $usdt_wallet = $row['usdt_wallet'];
        }
    }
    if($walletAmount > $amount){
        $utility_wallet_blns = $amount+$utilityWalletAmount;
        $wallet_balance_after_deduction = $walletAmount - $amount;
        $user ->updated_balance_utility = $utility_wallet_blns;
        $user ->updated_balance = $wallet_balance_after_deduction;

         //set up transaction details for debit wallet
        $transaction_wallet->user_id = $data->user_id;
        $transaction_wallet->amount = $amount;
        $transaction_wallet->status = $debit;
        $transaction_wallet->transaction_type = $wallet_transfer_utility;
        $transaction_wallet->affected_wallet = $wallet;
        $transaction_wallet->payment_for = $wallet_transfer_utility;
        $transaction_wallet->mobile_number = NULL;
        $transaction_wallet->consumer_number = NULL;
        $transaction_wallet->operator_code = NULL;
        $transaction_wallet->flight_no = NULL;
        $transaction_wallet->wallet_balance = $wallet_balance_after_deduction;
        $transaction_wallet->utility_balance = $utilityWalletAmount;
        $transaction_wallet->investment_balance = $investment_wallet;
        $transaction_wallet->earning_balance = $earnings_wallet;
        $transaction_wallet->usdt_wallet = $usdt_wallet;

        //set up transaction details for credit utility wallet
        $transaction_utility_wallet->user_id = $data->user_id;
        $transaction_utility_wallet->amount = $amount;
        $transaction_utility_wallet->status = $credit;
        $transaction_utility_wallet->transaction_type = $wallet_transfer_utility;
        $transaction_utility_wallet->affected_wallet = $utility_wallet;
        $transaction_utility_wallet->payment_for = $wallet_transfer_utility;
        $transaction_utility_wallet->mobile_number = NULL;
        $transaction_utility_wallet->consumer_number = NULL;
        $transaction_utility_wallet->operator_code = NULL;
        $transaction_utility_wallet->flight_no = NULL;
        $transaction_utility_wallet->wallet_balance = $wallet_balance_after_deduction;
        $transaction_utility_wallet->utility_balance = $utility_wallet_blns;
        $transaction_utility_wallet->investment_balance = $investment_wallet;
        $transaction_utility_wallet->earning_balance = $earnings_wallet;
        $transaction_utility_wallet->usdt_wallet = $usdt_wallet;

        $user->UpdateUtilityWallet();
        if($transaction_wallet->create() && $transaction_utility_wallet->create()){
            
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "Recharge Successfully Completed.") );
            
        } else{
            http_response_code(404);
            echo json_encode(
                array("status" => "Failed", "message" => "Something is wrong please try again.")
            );
        }
    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Insufficient balance in your account")
        );
    }
   

    
    

?>