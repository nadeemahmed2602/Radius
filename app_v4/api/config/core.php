<?php
// show error reporting
error_reporting(E_ALL);

// set your default time-zone
date_default_timezone_set('Asia/Manila');

// variables used for jwt
$key = "example_key";
$iss = "http://example.org";
$aud = "http://example.com";
$iat = 1356999524;
$nbf = 1357000000;

# Image Baseurl URL
$Fast2SmsApiKey = "b3lF6snCKSHdPhjIgy4LftGDzuoXVvpMxQa5meU90YZcirR8q2nOxMLoy90ztSjKgQXqp1i2hIerud5J";

# SMTP Details
$smtpHost = "smtp.gmail.com";
$smtpUsername = "vrushali@radiuschain.net";
$smtpPassword = "~t6LWgIt~}ee555";
$smtpSecure = "ssl";
$smtpPort = 465;
$setFromEmailAddress = "vrushali@radiuschain.net";
$adminEmailAddress = "sonivrushali1234@gmail.com";

//transaction  type
$wallet_deposit = "wallet_deposit"; 
$wallet_withdraw = "wallet_withdraw" ;
$wallet_transfer_utility = "wallet_transfer_utility";
$recharge_mobile = "recharge_mobile"; 
$recharge_utility = "recharge_utility";
$recharge_cylinder = "recharge_cylinder";
$flight_booking = "flight_booking";
$investment = "investment";
$ROI_income = "ROI_income"; 
$Level_income = "Level_income"; 
$ROI_Level_income_own = "ROI_Level_income_own";
$ROI_Level_income_downline = "ROI_Level_income_downline";
$internal_transfer = "internal_transfer";
$SCAN_AND_PAY = "SCAN_AND_PAY";

// transaction status
$debit = "debit";
$credit = "credit";

//affected wallet
$wallet = "wallet";
$utility_wallet = "utility_wallet";
$investment_wallet = "investment_wallet";
$earnings_wallet = "earnings_wallet";
$usdt_wallet = "usdt_wallet";
$scan_and_pay = "scan_and_pay";


// booking status
$success = 1;
$fail = 0;
$pending = 2;

// booking/recharge type
$mobilerecharge = "mobilerecharge"; 
$utilityBillPay = "utilityBillPay";
$gas = "gas";
$flight = "flight";


// payment for 
// $recharge
// $utility
// $cylynder
// $flight
// $walletDeposit
// $walletWithdraw
// $walletTransferUtility

?>