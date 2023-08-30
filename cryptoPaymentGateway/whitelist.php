<?php
ob_start();
$txnid = $_GET['txnid'];

$type = "BNB";
$address = $_GET['address'];

$CustomerId = $_GET['CustomerId'];
$CustomerEmail = $_GET['CustomerEmail'];
$CustomerPhone = $_GET['CustomerPhone'];
$CustomerName = $_GET['CustomerName'];
$MERCHANT_KEY = "fHZF/nptlfRDv57mcl2g+w=="; // Merchant Key From App
$SuccessUrl ="https://yourdomain/whitelistsuccess.php";
$FailureUrl ="https://yourdomain/whitelistfailure.php?transactionId=".$txnid;
//$FailureUrl ="https://yourdomain/failure.php?transactionId=".$txnid;
$PAYU_BASE_URL = "https://customerservice.walletpayment.net/api/v1/whitelist";

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$payload = json_encode(
    [
        'iss'=> 'walletpayment.net',
        'exp' => strtotime("+15 minutes"),
        'txnid' => $txnid,
        'type' => $type,
        'address' => $address,
        'customerId' => $CustomerId,
        'customerName' => $CustomerName,
        'customerEmail' => $CustomerEmail,
        'customerPhone' => $CustomerPhone,
        'minCoins' => 0.002,
        'maxCoins' => 0.3,
        'successUrl' => $SuccessUrl,
        'failureUrl' => $FailureUrl
    ]
);
$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $MERCHANT_KEY, true);
$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

$cURLConnection = curl_init();
curl_setopt($cURLConnection, CURLOPT_URL, $PAYU_BASE_URL);
//print_r($jwt);
//echo $_SERVER['SERVER_NAME'];
//exit();

$headr = array();
$headr[] = 'Content-length: 0';
$headr[] = 'Content-type: application/json';
$headr[] = 'Authorization: '.$jwt;
$headr[] = 'Access-Control-Allow-Origin: '.$_SERVER['SERVER_NAME'];
$agent= 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';

curl_setopt($cURLConnection, CURLOPT_USERAGENT, $agent);
curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
//curl_setopt($cURLConnection, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($cURLConnection, CURLOPT_POST, true);
curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
//curl_setopt($cURLConnection, CURLOPT_MAXREDIRS, 10);

$response= curl_exec($cURLConnection);

if(curl_errno($cURLConnection)){
	echo $_SERVER['SERVER_NAME'];
    echo 'Request Error:' . curl_error($cURLConnection);
    exit;
}
else{
	$url = str_replace("Found. Redirecting to ", "", $response);
	$url = trim($url);
    $obj = json_decode($response);
	$error = 'Invalid';

  	if(isset($obj->message)) {
      $error = $obj->message;
      $error = str_replace(' ', '%20', $error);
      $url = 'https:/yourdomain/user/whitelist_success_response?address='.$address.'&error='.$error;
    } else {
      $url = 'https://yourdomain/user/whitelist_success_response?address='.$address;
    }
    
    header('Location: '.$url);
}

curl_close($cURLConnection);
?>
