<?php
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(E_ALL);
include("init_payment.php");

// Validate and sanitize input parameters
$amount = isset($_GET['amount']) ? floatval($_GET['amount']) : 0.00; // Convert to float
$type = isset($_GET['type']) ? $_GET['type'] : '';
$address = isset($_GET['address']) ? $_GET['address'] : '';
$transactionId = isset($_GET['transactionId']) ? $_GET['transactionId'] : '';
$customerName = isset($_GET['customerName']) ? $_GET['customerName'] : '';
$customerEmail = isset($_GET['customerEmail']) ? $_GET['customerEmail'] : '';
$customerPhone = isset($_GET['customerPhone']) ? $_GET['customerPhone'] : '';
$userId = isset($_GET['userId']) ? $_GET['userId'] : '';

// Construct the success URL with proper parameter separation
$successUrl = "https://radiuschain.net/cryptoPaymentGateway/rechargeSuccess.php?user_id=".$userId."&amount=".$amount;

// Call the sendPaymentDeposit function with validated and sanitized data
sendPaymentDeposit(
    uniqid(),
    $amount,
    $type,
    $address,
    $transactionId,
    $customerName,
    $customerEmail,
    $customerPhone,
    $successUrl,
    "https://radiuschain.net/cryptoPaymentGateway/rechargeFailer.php",
    "http://radiuschain.net/cryptoPaymentGateway/webhook.php"
);
?>
