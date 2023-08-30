<?php
ob_start();
$txnid = $_GET['txnid'];
if(isset($_GET['type'])) {
$type = $_GET['type'];
} else {
  $type = 'BNB';
}
$address = $_GET['address'];
$amount = $_GET['amount'];

$CustomerId = $_GET['CustomerId'];
$CustomerEmail = $_GET['CustomerEmail'];
$CustomerPhone = $_GET['CustomerPhone'];
$CustomerName = $_GET['CustomerName'];
$MERCHANT_KEY = "fHZF/nptlfRDv57mcl2g+w=="; // Merchant Key From App
$SuccessUrl ="https://yourdomain/withdraw_success.php";
$FailureUrl ="https://yourdomain/withdraw_failure.php";
$PAYU_BASE_URL = "https://customerservice.walletpayment.net/api/v1/requests";
$webhookUrl ="https://yourdomain/withdraw_hook.php";
//$webhookUrl ="https://yourdomain/update_withdraw_status";
$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$payload = json_encode(
    [
        'iss'=> 'walletpayment.net',
        'exp' => strtotime("+15 minutes"),
        'txnid' => $txnid,
        'amount' => $amount,
        'type' => $type,
        'address' => $address,
        'cid' => $CustomerId,
        'name' => $CustomerName,
        'email' => $CustomerEmail,
        'mobile' => $CustomerPhone,
      	'webhookUrl'=> $webhookUrl
    ]
);
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $MERCHANT_KEY, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

$cURLConnection = curl_init();
curl_setopt($cURLConnection, CURLOPT_URL, $PAYU_BASE_URL);

$headr = array();
$headr[] = 'Content-length: 0';
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: '.$jwt;
$headr[] = 'Access-Control-Allow-Origin: '.$_SERVER['SERVER_NAME'];
$agent= 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';

curl_setopt($cURLConnection, CURLOPT_USERAGENT, $agent);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
curl_setopt($cURLConnection, CURLOPT_POST, true);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

$response= curl_exec($cURLConnection);

if(curl_errno($cURLConnection)){
	echo $_SERVER['SERVER_NAME'];
    echo 'Request Error:' . curl_error($cURLConnection);
    exit;
}
else{
	$obj = json_decode($response);

    if(isset($obj->status)) {
        $url = $SuccessUrl;
    } else {
        if(isset($obj->message)) {
        $error = $obj->message;
        } else {
          $error = $response;
        }
        $error = str_replace(' ', '%20', $error);
        $url = $FailureUrl.'?error='.$error;
    }
    header('Location: '.$url);
}

curl_close($cURLConnection);
?>
