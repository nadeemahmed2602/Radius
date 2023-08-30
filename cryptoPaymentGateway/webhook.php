<?php
include("init_payment.php");
include("connection.php");

// if (validateResult($jwt)) {
// 	  $response = getResponseResult($jwt);
// 	  echo $response->referenceNo; // Our ID in our Database
// 	  echo $response->transactionId; // your ID in your Database
// 	  echo $response->transactionhash; // blockchain hash

	  $referenceNo = "123";
	  $transactionId = "123";
	  $transactionhash = "aaa";
	  $user_id = "752";
	  $amount = 10;
	  $convertedAmount = 0;
	  $curType = "TUSDT"; //"TUSDT","TRX","BNB"
	
// 	  1 TUSDT = 1 USD
// 15 trx = 1 USD
// 1 BNB = 250 USD

	 
	  $wallet_amount = 0;
	  $utilityWallet = 0;
	  $investment_wallet = 0;
	  $earnings_wallet = 0;
	  // get wallet balance 
	  $q_get_user_info = "select * from customer where id='$user_id'";
	  $res_get_user_info = mysqli_query($con, $q_get_user_info);
	  if (mysqli_num_rows($res_get_user_info) > 0) {
		while($row = mysqli_fetch_assoc($res_get_user_info)) {            
			$wallet_amount = $row['wallet_amount'];
			$utilityWallet = $row['utilityWallet'];
			$investment_wallet = $row['investment_wallet'];
			$earnings_wallet = $row['earnings_wallet'];
		}  
	  }

	  
	  // perform conversion here and update $amount
	  if($curType == "TUSDT"){
		$convertedAmount = $amount;
	  }else if($curType == "TRX"){
		$convertedAmount = $amount/15;
	  }else if($curType == "BNB"){
		$convertedAmount = $amount*250;
	  }else{
		$convertedAmount = $amount;
	  }

	  // amount after update
	  $walletDepositAmount = $wallet_amount + $convertedAmount;

	  $q_add_amount_in_wallet = "UPDATE customer SET wallet_amount= $walletDepositAmount where id= $user_id";
	  $res_add_amount_in_wallet = mysqli_query($con, $q_add_amount_in_wallet);
	  if ($res_add_amount_in_wallet > 0) {
		$q_transaction = "INSERT INTO `transaction` (`id`, `user_id`, `amount`, `dateTime`, `status`, `transaction_type`, `affected_wallet`, `payment_for`, `mobile_number`, `consumer_number`, `operator_code`, `flight_no`, `wallet_balance`, `utility_balance`, `investment_balance`, `earning_balance`) VALUES (NULL, '$user_id', '$convertedAmount', CURRENT_TIMESTAMP, 'credit', 'wallet_deposit', 'wallet', 'wallet_deposit', NULL, NULL, NULL, NULL, '$walletDepositAmount', '0', '0', '0');";
		$res_transaction = mysqli_query($con, $q_transaction);
		// insert record in transaction table
		http_response_code(200);
        echo json_encode(array("status" => "Success", "message" => "Deposit done.") );
	  }else{
		http_response_code(400);
        echo json_encode(array("status" => "Success", "message" => "Error while depositing") );
	  }
	//   date_default_timezone_set('Asia/Kolkata');
	// 	$logDateTime = date('Y-m-d H:i:s', time());
	// 	file_put_contents('logs/logs1.txt', "\n" .'*********:Payload Data webhook  ' . $response . '*********', FILE_APPEND);

// } else {
// 	echo "Invalid";
// 	}

?>