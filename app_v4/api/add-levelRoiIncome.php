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
$transaction_investment = new transaction($db);

// new obj
$obj_get_all_user = new transaction($db);

// for entry in transaction table
$transaction_investment_20 = new transaction($db);
$transaction_investment_80 = new transaction($db);


// get posted data
$data = json_decode(file_get_contents("php://input"));

// $user ->user_id = $data->user_id;
// $user2->user_id = $data->user_id;
// $user ->amount = $data->amount;
// $amount = $data->amount;
$count = 0;

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
$stmt_get_levels = $userGetLevel->getRoiLevels();
$item_count_get_levels = $stmt_get_levels->rowCount();
if($item_count_get_levels > 0){
    while ($user_row = $stmt_get_levels->fetch(PDO::FETCH_ASSOC)){
        $varLevelPercentage = $user_row['levelPercentage'];
        array_push($levelPercentage,$varLevelPercentage);
    }
}
echo "---------------------------------";
echo print_r($levelPercentage);
echo "---------------------------------";


// get all user list
$stmt_all_users = $obj_get_all_user->getAllUser();
$item_count_all_user = $stmt_all_users->rowCount();
if($item_count_all_user > 0){
    while ($user_row = $stmt_all_users->fetch(PDO::FETCH_ASSOC)){

        // for parent 
        $parentUserId = array();
        $parentwallet = array();
        $parentUtilityWallet = array();
        $parentInvestmentWallet = array();
        $parentEarningWallet= array();
        $parentusdt_wallet= array();

        $walletAmount = 0;
        $utilityWalletAmount = 0;
        $investment_wallet2 = 0;
        $earnings_wallet2 = 0;
        $usdt_wallet = 0;

        $user_id = $user_row['id'];
        $name = $user_row['name'];
        $walletAmount = $user_row['wallet_amount'];
        $utilityWalletAmount = $user_row['utilityWallet'];
        $investment_wallet2 = $user_row['investment_wallet'];
        $earnings_wallet2 = $user_row['earnings_wallet'];  
        $usdt_wallet = $user_row['usdt_wallet'];
        $obj_get_parent = new transaction($db);
        $obj_get_parent->user_id = $user_id;

         //give ROI to current user based on daily
        $tr_roi_current_user = new transaction($db);
        $roi = 0;
        $roi = $investment_wallet2 * $ROI_income_per /100;
        $roi_amountper80 = 80 * $roi / 100;
        $roi_amountper20 = 20 * $roi / 100;
        $tr_roi_current_user->user_id = $user_id;
        $tr_roi_current_user->investment_wallet_80per = $roi_amountper80;  
        $tr_roi_current_user->utility_wallet_20per = $roi_amountper20;  
  

        if($tr_roi_current_user->addROIIncome()){
              // transaction for investment (utility wallet) 20%
            $transaction_investment_20->user_id =  $user_id;
            $transaction_investment_20->amount = $roi_amountper20;
            $transaction_investment_20->status = $credit;
            $transaction_investment_20->transaction_type = $ROI_income;
            $transaction_investment_20->affected_wallet = $utility_wallet;
            $transaction_investment_20->payment_for = $ROI_income;
            $transaction_investment_20->mobile_number = NULL;
            $transaction_investment_20->consumer_number = NULL;
            $transaction_investment_20->operator_code = NULL;
            $transaction_investment_20->flight_no = NULL;
            $transaction_investment_20->wallet_balance = $walletAmount;
            $transaction_investment_20->utility_balance = $utilityWalletAmount + $roi_amountper20;
            $transaction_investment_20->investment_balance = $investment_wallet2 ;
            $transaction_investment_20->earning_balance = $earnings_wallet2;
            $transaction_investment_20->usdt_wallet = $usdt_wallet;
            $transaction_investment_20->create();

             
              // transaction for investment (investment wallet) 80%
             $transaction_investment_80->user_id =  $user_id;
             $transaction_investment_80->amount = $roi_amountper80;
             $transaction_investment_80->status = $credit;
             $transaction_investment_80->transaction_type = $ROI_income;
             $transaction_investment_80->affected_wallet = $investment_wallet;
             $transaction_investment_80->payment_for = $ROI_income;
             $transaction_investment_80->mobile_number = NULL;
             $transaction_investment_80->consumer_number = NULL;
             $transaction_investment_80->operator_code = NULL;
             $transaction_investment_80->flight_no = NULL;
             $transaction_investment_80->wallet_balance = $walletAmount;
             $transaction_investment_80->utility_balance = $utilityWalletAmount;
             $transaction_investment_80->investment_balance = $investment_wallet2 + $roi_amountper80 ;
             $transaction_investment_80->earning_balance = $earnings_wallet2;
             $transaction_investment_80->usdt_wallet = $usdt_wallet;
             $transaction_investment_80->create();
            
        }

        // ===================get all uplines =====================================//
        // logic to get 15 level parent
        for($i = 0; $i < 15 ; $i++){
            $stmt_get_parent = $obj_get_parent->getParent();
            $itemCountGetParent = $stmt_get_parent->rowCount();
            if($itemCountGetParent > 0){
                while ($row = $stmt_get_parent->fetch(PDO::FETCH_ASSOC)){
                    $parentId = $row['id'];
                    $wallet_amount = $row['wallet_amount'];
                    $utilityWallet = $row['utilityWallet'];
                    $investment_wallet2_var = $row['investment_wallet'];
                    $earnings_wallet2_var = $row['earnings_wallet'];
                    $usdt_wallet_var = $row['usdt_wallet'];
                    array_push($parentUserId,$parentId);
                    array_push($parentwallet,$wallet_amount);
                    array_push($parentUtilityWallet,$utilityWallet);
                    array_push($parentInvestmentWallet,$investment_wallet2_var);
                    array_push($parentEarningWallet,$earnings_wallet2_var);    
                    array_push($parentusdt_wallet,$usdt_wallet_var);             
                    $obj_get_parent->user_id = $row['parent_id'];
                }
            }
        }
        array_shift($parentUserId);
        array_shift($parentwallet);
        array_shift($parentUtilityWallet);
        array_shift($parentInvestmentWallet);
        array_shift($parentEarningWallet);
        array_shift($parentusdt_wallet);

        $tr_roi_to_parent_user = new transaction($db);
        $tr_roi_to_parent_user->parentUserId = $parentUserId;
        $tr_roi_to_parent_user->level_income = $roi;

        // if($tr_roi_to_parent_user->updateLevelIncome()){
        if(count($parentUserId) > 0){
            $j = 0;
            foreach($parentUserId as $varParentId){

                // calculate roi 
                $roi_var = $parentInvestmentWallet[$j] * $ROI_income_per /100;

                $level_per = $levelPercentage[$j];
                $levelAmount = $roi_var * $level_per /100;
            
                $tr_roi_to_parent_user_2 = new transaction($db);
                $tr_roi_to_parent_user_2->user_id =  $varParentId;
                $tr_roi_to_parent_user_2->amount = $levelAmount;
                $tr_roi_to_parent_user_2->status = $credit;
                $tr_roi_to_parent_user_2->transaction_type = $ROI_Level_income_downline;
                $tr_roi_to_parent_user_2->affected_wallet = $earnings_wallet;
                $tr_roi_to_parent_user_2->payment_for = $ROI_Level_income_downline;
                $tr_roi_to_parent_user_2->mobile_number = NULL;
                $tr_roi_to_parent_user_2->consumer_number = NULL;
                $tr_roi_to_parent_user_2->operator_code = NULL;
                $tr_roi_to_parent_user_2->flight_no = NULL;
                $tr_roi_to_parent_user_2->wallet_balance = $parentwallet[$j];
                $tr_roi_to_parent_user_2->utility_balance = $parentUtilityWallet[$j];
                $tr_roi_to_parent_user_2->investment_balance = $parentInvestmentWallet[$j];
                $tr_roi_to_parent_user_2->earning_balance = $parentEarningWallet[$j] + $levelAmount;
                $tr_roi_to_parent_user_2->usdt_wallet = $parentusdt_wallet[$j];

                $tra_level_income = new transaction($db);
                $tra_level_income->level_income = $levelAmount ;
                $tra_level_income->parentUserId = $varParentId;

                echo "\n parentId: ".$varParentId;
                echo "\n level: ".$j;
                echo "\n level (%): ".$levelPercentage[$j];
                echo "\n earnings_wallet2: ".$earnings_wallet2;
                echo "\n levelAmount:".$levelAmount;
                if($tra_level_income->updateROILevelIncomeSingle()){
                    $tr_roi_to_parent_user_2->create();
                }
                // $tr_roi_to_parent_user_2->create();
                $j++;
            } 
        }
        // }
        echo "\n User name: $name";
        echo "\n -----------------------\n";
        echo "Parent List:\n";
        print_r($parentUserId);
        echo "-----------------------\n";
        echo "Done";
    }
}
?>