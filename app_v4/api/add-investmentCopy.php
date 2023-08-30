<?php

// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include_once 'config/database.php';
include_once 'config/core.php';
include_once 'objects/transaction.php';
include_once 'objects/wallet.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new transaction($db);
$user2 = new User($db);
$transaction_roi = new transaction($db);
$transaction_level= new transaction($db);
$transaction_investment = new transaction($db);

// for entry in transaction table
$transaction_roi_2 = new transaction($db);
$transaction_level_2= new transaction($db);
$transaction_investment_2 = new transaction($db);

// check email existence here

// get posted data
$data = json_decode(file_get_contents("php://input"));

$user ->user_id = $data->user_id;
$user2->user_id = $data->user_id;
$user ->amount = $data->amount;
$amount = $data->amount;
$count = 0;
$parentUserId = array();
$parentwallet = array();
$parentUtilityWallet = array();
$parentInvestmentWallet = array();
$parentEarningWallet= array();

$stmt_wallet_bal = $user2->getWalletBalance();
$itemCountWallet = $stmt_wallet_bal->rowCount();
$walletAmount = 0;
$utilityWalletAmount = 0;
$investment_wallet2 = 0;
$earnings_wallet2 = 0;
    if($itemCountWallet > 0){
        while ($row = $stmt_wallet_bal->fetch(PDO::FETCH_ASSOC)){
            $walletAmount = $row['wallet_amount']."\n";
            $utilityWalletAmount = $row['utilityWallet']."\n";
            $investment_wallet2 = $row['investment_wallet']."\n";
            $earnings_wallet2 = $row['earnings_wallet']."\n";
        }
    }

    // echo "wallet_amount:".$walletAmount;
    // echo "utilityWalletAmount:".$utilityWalletAmount;
    // echo "investment_wallet:".$investment_wallet2;
    // echo "earnings_wallet:".$earnings_wallet2;


// ===================get all uplines =====================================//
// logic to get 15 level parent
for($i = 0; $i <= 15 ; $i++){
    $stmt = $user->getParent();
    $itemCount = $stmt->rowCount();
    if($itemCount > 0){
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // $amout = $row['wallet_amount'];
            $parentId = $row['id'];
            $wallet_amount = $row['wallet_amount'];
            $utilityWallet = $row['utilityWallet'];
            $investment_wallet2 = $row['investment_wallet'];
            $earnings_wallet2 = $row['earnings_wallet'];
            // if($parentId != 0){
                array_push($parentUserId,$parentId);
                array_push($parentwallet,$wallet_amount);
                array_push($parentUtilityWallet,$utilityWallet);
                array_push($parentInvestmentWallet,$investment_wallet2);
                array_push($parentEarningWallet,$earnings_wallet2);
            // }
            
            $user ->user_id = $row['parent_id'];
        }
    }
}
array_shift($parentUserId);
array_shift($parentwallet);
array_shift($parentUtilityWallet);
array_shift($parentInvestmentWallet);
array_shift($parentEarningWallet);
// echo print_r($parentUserId); // all parent for level income
// echo "----wallet------";
// echo print_r($parentwallet);
// echo "-----parentUtilityWallet-----";

// echo print_r($parentUtilityWallet);
// echo "-----parentInvestmentWallet-----";

// echo print_r($parentInvestmentWallet);
// echo "-----parentEarningWallet-----";

// echo print_r($parentEarningWallet);
// echo "----------";

// ===========================================================================//

// ===================get all income percentage from admin panel=====================================//

// get income percentage
// getIncomePercentage
$stmt2 = $user->getIncomePercentage();
$itemCount2 = $stmt2->rowCount();
$ROI_income_per = 0;
$level_income_per = 0;
$ROI_level_income_per = 0;
if($itemCount2 > 0){
    while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
        $ROI_income_per = $row2['ROI_income'];
        $level_income_per = $row2['level_income'];
        $ROI_level_income_per = $row2['ROI_level_income'];
    }
}

// echo $ROI_income_per."\n";
// echo $level_income_per."\n";
// echo $ROI_level_income_per."\n";
// ==========================================================================//


// add investment 
$transaction_investment->user_id = $data->user_id;
$transaction_investment->investment_wallet = $amount;

// calculate ROI income for user and update earmings
$roi = 0;
$roi = $amount * $ROI_income_per /100;
$transaction_roi->user_id = $data->user_id;
$transaction_roi->roi_income = $roi;


// calculate one time Level income
$level_income = 0;
$level_income = $amount * $level_income_per /100;
$transaction_level->parentUserId = $parentUserId;
$transaction_level->level_income = $level_income;

if($transaction_investment->UpdateInvestmentWallet()){
    // transaction for investment
    // transactionInvesment();
    $transaction_investment_2->user_id = $data->user_id;
    $transaction_investment_2->amount = $amount;
    $transaction_investment_2->status = $credit;
    $transaction_investment_2->transaction_type = $investment;
    $transaction_investment_2->affected_wallet = $investment_wallet;
    $transaction_investment_2->payment_for = $investment;
    $transaction_investment_2->mobile_number = NULL;
    $transaction_investment_2->consumer_number = NULL;
    $transaction_investment_2->operator_code = NULL;
    $transaction_investment_2->flight_no = NULL;
    $transaction_investment_2->wallet_balance = $walletAmount;
    $transaction_investment_2->utility_balance = $utilityWalletAmount;
    $transaction_investment_2->investment_balance = $investment_wallet2 + $amount;
    $transaction_investment_2->earning_balance = $earnings_wallet2;
    $transaction_investment_2->create();
    // if($transaction_roi->addROIIncome()){
    if(true){
        // transaction for ROI
        // transactionROIIncome();
        $transaction_roi_2->user_id = $data->user_id;
        $transaction_roi_2->amount = $roi;
        $transaction_roi_2->status = $credit;
        $transaction_roi_2->transaction_type = $ROI_income;
        $transaction_roi_2->affected_wallet = $earnings_wallet;
        $transaction_roi_2->payment_for = $ROI_income;
        $transaction_roi_2->mobile_number = NULL;
        $transaction_roi_2->consumer_number = NULL;
        $transaction_roi_2->operator_code = NULL;
        $transaction_roi_2->flight_no = NULL;
        $transaction_roi_2->wallet_balance = $walletAmount;
        $transaction_roi_2->utility_balance = $utilityWalletAmount;
        $transaction_roi_2->investment_balance = $investment_wallet2 + $amount;
        $transaction_roi_2->earning_balance = $earnings_wallet2 + $roi;
        // $transaction_roi_2->create();
        if(count($parentUserId) > 0){
            if($transaction_level->updateLevelIncome()){
                // transaction for level
                $j = 0;
                foreach($parentUserId as $varParentId){
                    $transaction_level_2->user_id = $varParentId;
                    $transaction_level_2->amount = $level_income;
                    $transaction_level_2->status = $credit;
                    $transaction_level_2->transaction_type = $Level_income;
                    $transaction_level_2->affected_wallet = $earnings_wallet;
                    $transaction_level_2->payment_for = $Level_income;
                    $transaction_level_2->mobile_number = NULL;
                    $transaction_level_2->consumer_number = NULL;
                    $transaction_level_2->operator_code = NULL;
                    $transaction_level_2->flight_no = NULL;
                    $transaction_level_2->wallet_balance = $parentwallet[$j];
                    $transaction_level_2->utility_balance = $parentUtilityWallet[$j];
                    $transaction_level_2->investment_balance = $parentInvestmentWallet[$j];
                    $transaction_level_2->earning_balance = $parentEarningWallet[$j] + $level_income;
                    $transaction_level_2->create();
                    $j++;
                }
    
                http_response_code(200);
                echo json_encode(array("status" => "Success", "message" => "Investement Done.") );
            }else{
                http_response_code(404);
                echo json_encode(
                    array("status" => "Failed", "message" => "Something is wrong while giving level income.")
                );
            }
        }else{
            http_response_code(200);
            echo json_encode(array("status" => "Success", "message" => "Investement Done.") );
        }
       
    }else{
        http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong while giving ROI income.")
        );
    }
}else{
    http_response_code(404);
        echo json_encode(
            array("status" => "Failed", "message" => "Something is wrong while investing amount.")
        );
}


?>