<?php

date_default_timezone_set('Asia/Kolkata');
$logDateTime = date('Y-m-d H:i:s', time());
file_put_contents('logs/logs1.txt', "\n" .'*********:Payload Data suceesssssssssssss  ' . $logDateTime . '*********', FILE_APPEND);

$userId = $_GET['user_id'];
$amount = $_GET['amount'];

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

?>
<html>
  <head>
    <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:400,400i,700,900&display=swap" rel="stylesheet">
  </head>
    <style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
    <body>
      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
        <h1>Success</h1> 
        <p>Deposit Done !<br/> Amount is beign added to your wallete. Refresh your page</p>
      </div>
    </body>
</html>