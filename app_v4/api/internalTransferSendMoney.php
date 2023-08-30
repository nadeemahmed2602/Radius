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
$user_sender = new User($db);
$user_receiver = new User($db);
$userUpdateSenderRecieverWalletAfterInternal = new User($db);
$transaction_sender = new transaction($db);
$transaction_receiver = new transaction($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

$sender_id = $data->sender_id;
$receiver_id = $data->receiver_id;
$amount = $data->amount;

// get sender's wallet balance
$user_sender->user_id = $sender_id;
$stmt = $user_sender->getWalletBalance();
$walletAmount = 0;
$utilityWallet = 0;
$investment_wallet2_var = 0;
$earnings_wallet2_var = 0;
$usdt_wallet2_var = 0;
$itemCount = $stmt->rowCount();
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $walletAmount = $row['wallet_amount'];
            $utilityWallet = $row['utilityWallet'];
            $investment_wallet2_var = $row['investment_wallet'];
            $earnings_wallet2_var = $row['earnings_wallet'];
            $usdt_wallet2_var = $row['usdt_wallet'];

        }
    }

// get sender's wallet balance
$user_receiver->user_id = $receiver_id;
$stmt = $user_receiver->getWalletBalance();
$walletAmountreceiver = 0;
$utilityWalletreceiver = 0;
$investment_wallet2_varreceiver = 0;
$earnings_wallet2_varreceiver = 0;
$usdt_wallet2_varreceiver = 0;
$itemCount = $stmt->rowCount();
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $walletAmountreceiver = $row['wallet_amount'];
            $utilityWalletreceiver = $row['utilityWallet'];
            $investment_wallet2_varreceiver = $row['investment_wallet'];
            $earnings_wallet2_varreceiver = $row['earnings_wallet'];
            $usdt_wallet2_varreceiver = $row['usdt_wallet'];

        }
    }

    // check if sender has enough balance

    $intwalletAmount = (double) $walletAmount;
    $intamount = (double) $amount;

    if($intwalletAmount >= $intamount){
        $userUpdateSenderRecieverWalletAfterInternal->sender_id = $sender_id;
        $userUpdateSenderRecieverWalletAfterInternal->reciever_id = $receiver_id;
        $userUpdateSenderRecieverWalletAfterInternal->updated_balance_sender = $walletAmount - $amount;
        $userUpdateSenderRecieverWalletAfterInternal->updated_balance_reciever = $walletAmountreceiver + $amount;

            //sender: set up transaction details (debit)
            $transaction_sender->user_id = $sender_id;
            $transaction_sender->amount = $amount;
            $transaction_sender->status = $debit;
            $transaction_sender->transaction_type = $internal_transfer;
            $transaction_sender->affected_wallet = $wallet;
            $transaction_sender->payment_for = $internal_transfer;
            $transaction_sender->mobile_number = NULL;
            $transaction_sender->consumer_number = NULL;
            $transaction_sender->operator_code = NULL;
            $transaction_sender->flight_no = NULL;
            $transaction_sender->wallet_balance = $walletAmount - $amount;
            $transaction_sender->utility_balance = $utilityWallet;
            $transaction_sender->investment_balance = $investment_wallet2_var;
            $transaction_sender->earning_balance = $earnings_wallet2_var;
            $transaction_sender->usdt_wallet = $usdt_wallet2_var;

            //reciver: set up transaction details (debit)
            $transaction_receiver->user_id = $receiver_id;
            $transaction_receiver->amount = $amount;
            $transaction_receiver->status = $credit;
            $transaction_receiver->transaction_type = $internal_transfer;
            $transaction_receiver->affected_wallet = $wallet;
            $transaction_receiver->payment_for = $internal_transfer;
            $transaction_receiver->mobile_number = NULL;
            $transaction_receiver->consumer_number = NULL;
            $transaction_receiver->operator_code = NULL;
            $transaction_receiver->flight_no = NULL;
            $transaction_receiver->wallet_balance = $walletAmountreceiver + $amount;
            $transaction_receiver->utility_balance = $utilityWalletreceiver;
            $transaction_receiver->investment_balance = $investment_wallet2_varreceiver;
            $transaction_receiver->earning_balance = $earnings_wallet2_varreceiver;
            $transaction_receiver->usdt_wallet = $usdt_wallet2_varreceiver;


            $userUpdateSenderRecieverWalletAfterInternal->UpdateSenderRecieverWalletAfterInternal();
            if($transaction_sender->create() && $transaction_receiver->create()){
                http_response_code(200);
                echo json_encode(array("status" => "Success", "message" => "Transfer Done.") );
                
            } else{
                http_response_code(404);
                echo json_encode(
                    array("status" => "Failed", "message" => "Something is wrong please try again.")
                );
            }

    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Insufficient wallet balance. Please deposit USD in your wallet.")
        );
    }
?>