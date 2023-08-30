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
$transaction_investment_10 = new transaction($db);
$transaction_investment_90 = new transaction($db);

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
$parentUSDTWallet= array();

$stmt_wallet_bal = $user2->getWalletBalance();
$itemCountWallet = $stmt_wallet_bal->rowCount();
$walletAmount = 0;
$utilityWalletAmount = 0;
$investment_wallet2 = 0;
$earnings_wallet2 = 0;
$usdt_wallet = 0;
    if($itemCountWallet > 0){
        while ($row = $stmt_wallet_bal->fetch(PDO::FETCH_ASSOC)){
            $walletAmount = $row['wallet_amount']."\n";
            $utilityWalletAmount = $row['utilityWallet']."\n";
            $investment_wallet2 = $row['investment_wallet']."\n";
            $earnings_wallet2 = $row['earnings_wallet']."\n";
            $usdt_wallet = $row['usdt_wallet']."\n";
        }
    }

    // echo "wallet_amount:".$walletAmount;
    // echo "utilityWalletAmount:".$utilityWalletAmount;
    // echo "investment_wallet:".$investment_wallet2;
    // echo "earnings_wallet:".$earnings_wallet2;


// ===================get all uplines =====================================//
// logic to get 15 level parent
for($i = 0; $i < 15 ; $i++){
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
            $USDTWallet = $row['usdt_wallet'];
            // if($parentId != 0){
                array_push($parentUserId,$parentId);
                array_push($parentwallet,$wallet_amount);
                array_push($parentUtilityWallet,$utilityWallet);
                array_push($parentInvestmentWallet,$investment_wallet2);
                array_push($parentEarningWallet,$earnings_wallet2);
                array_push($parentUSDTWallet,$USDTWallet);
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
array_shift($parentUSDTWallet);
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

// get 15 level income
$levelPercentage = array();
$userGetLevel = new transaction($db);
$stmt_get_levels = $userGetLevel->getLevels();
$item_count_get_levels = $stmt_get_levels->rowCount();
if($item_count_get_levels > 0){
    while ($user_row = $stmt_get_levels->fetch(PDO::FETCH_ASSOC)){
        $varLevelPercentage = $user_row['levelPercentage'];
        array_push($levelPercentage,$varLevelPercentage);
    }
}

// echo print_r($levelPercentage);
// echo $ROI_income_per."\n";
// echo $level_income_per."\n";
// echo $ROI_level_income_per."\n";
// ==========================================================================//


// calculate 90% and 10%
// add 90% in investment and 10% in utility
$amountper10 = 10 * $amount / 100;
$amountper90 = 90 * $amount / 100;

// echo "amountper10:".$amountper10;
// echo "amountper90:".$amountper90;

// add investment 
$transaction_investment->user_id = $data->user_id;
$transaction_investment->investment_wallet_90per = $amountper90 ;
$transaction_investment->utility_wallet_10per = $amountper10 ;
$transaction_investment->amount = $amount ;


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
$intwalletAmount = (double) $walletAmount;
$intamount = (double) $amount;

// echo "intwalletAmount:".$intwalletAmount;
// echo "intamount:".$intamount;

if($intwalletAmount >= $intamount){
    if($transaction_investment->UpdateInvestmentWallet()){
        // transaction for investment (investment wallet) 90%
        $transaction_investment_90->user_id = $data->user_id;
        $transaction_investment_90->amount = $amountper90;
        $transaction_investment_90->status = $credit;
        $transaction_investment_90->transaction_type = $investment;
        $transaction_investment_90->affected_wallet = $investment_wallet;
        $transaction_investment_90->payment_for = $investment;
        $transaction_investment_90->mobile_number = NULL;
        $transaction_investment_90->consumer_number = NULL;
        $transaction_investment_90->operator_code = NULL;
        $transaction_investment_90->flight_no = NULL;
        $transaction_investment_90->wallet_balance = $walletAmount;
        $transaction_investment_90->utility_balance = $utilityWalletAmount;
        $transaction_investment_90->investment_balance = $investment_wallet2 + $amountper90;
        $transaction_investment_90->earning_balance = $earnings_wallet2;
        $transaction_investment_90->usdt_wallet = $usdt_wallet;
        $transaction_investment_90->create();

        // transaction for investment (utility wallet) 10%
        $transaction_investment_10->user_id = $data->user_id;
        $transaction_investment_10->amount = $amountper10;
        $transaction_investment_10->status = $credit;
        $transaction_investment_10->transaction_type = $investment;
        $transaction_investment_10->affected_wallet = $utility_wallet;
        $transaction_investment_10->payment_for = $investment;
        $transaction_investment_10->mobile_number = NULL;
        $transaction_investment_10->consumer_number = NULL;
        $transaction_investment_10->operator_code = NULL;
        $transaction_investment_10->flight_no = NULL;
        $transaction_investment_10->wallet_balance = $walletAmount;
        $transaction_investment_10->utility_balance = $utilityWalletAmount + $amountper90;
        $transaction_investment_10->investment_balance = $investment_wallet2 + $amountper90;
        $transaction_investment_10->earning_balance = $earnings_wallet2;
        $transaction_investment_10->usdt_wallet = $usdt_wallet;
        $transaction_investment_10->create();
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
            $transaction_roi_2->usdt_wallet = $usdt_wallet;
            // $transaction_roi_2->create();
            if(count($parentUserId) > 0){
                // if($transaction_level->updateLevelIncome()){
                    // transaction for level
                    $j = 0;
                    foreach($parentUserId as $varParentId){
                        $level_per = $levelPercentage[$j];
                        $levelAmount = $amount * $level_per /100;
                        $transaction_level_2->user_id = $varParentId;
                        $transaction_level_2->amount = $levelAmount;
                        $transaction_level_2->status = $credit;
                        $transaction_level_2->transaction_type = $Level_income;
                        $transaction_level_2->affected_wallet = $usdt_wallet;
                        $transaction_level_2->payment_for = $Level_income;
                        $transaction_level_2->mobile_number = NULL;
                        $transaction_level_2->consumer_number = NULL;
                        $transaction_level_2->operator_code = NULL;
                        $transaction_level_2->flight_no = NULL;
                        $transaction_level_2->wallet_balance = $parentwallet[$j];
                        $transaction_level_2->utility_balance = $parentUtilityWallet[$j];
                        $transaction_level_2->investment_balance = $parentInvestmentWallet[$j];
                        $transaction_level_2->earning_balance = $parentEarningWallet[$j];
                        $transaction_level_2->usdt_wallet = $parentUSDTWallet[$j] + $levelAmount;

                        $tra_level_income = new transaction($db);
                        $tra_level_income->level_income = $levelAmount ;
                        $tra_level_income->parentUserId = $varParentId;

                        // echo "\n parentId: ".$varParentId;
                        // echo "\n level: ".$j;
                        // echo "\n level (%): ".$levelPercentage[$j];
                        if($tra_level_income->updateLevelIncomeSingle()){
                            $transaction_level_2->create();
                        }
                        $j++;
                    }
        
                    http_response_code(200);
                    echo json_encode(array("status" => "Success", "message" => "Investement Done.") );
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
}else{
    http_response_code(404);
    echo json_encode(
        array("status" => "Failed", "message" => "Insufficient wallet balance. Please deposit USD in your wallet.")
    );
}

?>