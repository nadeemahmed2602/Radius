<?php
ob_start();
$txnid = $_GET['txnid'];
$amount = (float)$_GET['amount'];
$type = "TRX";
$address = "TN7it5E6bG5fJyuvhh4SN1mGRU9PsbZS11";

$type = "BNB";
$address = "0x00e789539b53EaCD9915671955bcBCEFe251CFAF";

$CustomerId = $_GET['CustomerId'];
$CustomerEmail = $_GET['CustomerEmail'];
$CustomerPhone = $_GET['CustomerPhone'];
$CustomerName = $_GET['CustomerName'];
$MERCHANT_KEY = "mhXRpqnTLHDJ+S6EMJvVRQ=="; // Merchant Key From App
$SuccessUrl ="https://towercrypto.uk/public/success.php";
$FailureUrl ="https://towercrypto.uk/public/failure.php?transactionId=".$txnid;
//$FailureUrl ="https://towercrypto.uk/public/failure.php?transactionId=".$txnid;
$PAYU_BASE_URL = "https://cwp-customer-service.firebaseapp.com/api/v1/deposits";
//$PAYU_BASE_URL = "https://customerservice.walletpayment.net/api/v1/deposits";

$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
$payload = json_encode(
    [
        'iss'=> 'walletpayment.net',
        'exp' => strtotime("+7 minutes"),
        'txnid' => $txnid,
        'amount' => $amount,
        'type' => $type,
        'address' => $address,
        'cid' => $CustomerId,
        'name' => $CustomerName,
        'email' => $CustomerEmail,
        'mobile' => $CustomerPhone,
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
  
}
else{
	$url = str_replace("Found. Redirecting to ", "", $response);
	$url = trim($url);
//$cookie_expire = time() + 50400;
//setcookie('city', "Test", $cookie_expire, '/');
echo $url;
 header("Location: ".$url);
}

curl_close($cURLConnection);
?>
