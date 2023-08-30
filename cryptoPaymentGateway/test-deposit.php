<?php
$userId = $_GET["user_id"];
$amount = $_GET["amount"];

$data = array(
    'user_id' => $userId,
    'amount' => $amount
);

$apiUrl = "http://radiuschain.net/app_v4/api/add-wallet.php";

$ch = curl_init($apiUrl);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);

// if ($response === false) {
//     echo "cURL Error: " . curl_error($ch);
// } else {
//     $responseData = json_decode($response, true);
//     if ($responseData['status'] == "Success") {
//         echo "Wallet Amount Deposit Done";
//     } else {
//         echo "Error While adding deposit";
//     }
//     echo 'WALLET LIST RESPONSE:' . json_encode($responseData);
// }

curl_close($ch);
?>