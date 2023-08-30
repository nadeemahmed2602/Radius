<?php

function base64UrlEncode($text)
{
  return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($text));
}

function sendPaymentDeposit($transactionID, $coins, $type, $address, $customerId, $customerName, $customerEmail, $customerPhone, $successUrl, $failureUrl, $webhookUrl)
{
  $secret  = "mhXRpqnTLHDJ+S6EMJvVRQ==";
  $depositUrl = "https://customerservice.walletpayment.net/api/v1/deposits";
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  $payload = json_encode([
    'iss' => 'walletpayment.net', 'exp' => strtotime("+7 minutes"), 'txnid' => $transactionID, 'amount' => $coins, 'type' => $type, 'address' => $address, 'cid' => $customerId, 'name' => $customerName, 'email' => $customerEmail, 'mobile' => $customerPhone, 'successUrl' => $successUrl, 'failureUrl' => $failureUrl, 'webhookUrl' => $webhookUrl

  ]);
  $base64UrlHeader = base64UrlEncode($header);
  $base64UrlPayload = base64UrlEncode($payload);
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
  $base64UrlSignature = base64UrlEncode($signature);
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

  $cURLConnection = curl_init();
  curl_setopt($cURLConnection, CURLOPT_URL, $depositUrl);

  $headr = array();
  $headr[] = 'Content-length: 0';
  $headr[] = 'Content-type: application/json';
  $headr[] = 'Authorization: ' . $jwt;
  $headr[] = 'Access-Control-Allow-Origin: ' . $_SERVER['SERVER_NAME'];
  $agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';
  curl_setopt($cURLConnection, CURLOPT_USERAGENT, $agent);
  curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
  curl_setopt($cURLConnection, CURLOPT_POST, true);
  curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($cURLConnection);
  $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
  if (curl_errno($cURLConnection)) {
    echo $_SERVER['SERVER_NAME'];
    echo 'Request Error:' . curl_error($cURLConnection);
  } else {
    if ($httpcode == 302 || $httpcode == 301) {
      $url = str_replace("Found. Redirecting to ", "", $response);
      $url = trim($url);
      header("Location: " . $url);
    } else {
      echo $response;
    }
  }

  curl_close($cURLConnection);
}

function sendPaymentWithdraw($transactionID, $coins, $type, $address, $customerId, $customerName, $customerEmail, $customerPhone, $webhookUrl)
{
  $secret  = "jj/Ix1eg0JCltEyFcUtG6A==";
  $withdrawUrl = "https://customerservice.walletpayment.net/api/v1/requests";
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  $payload = json_encode([
    'iss' => 'walletpayment.net', 'exp' => strtotime("+7 minutes"), 'txnid' => $transactionID, 'amount' => $coins, 'type' => $type, 'address' => $address, 'cid' => $customerId, 'name' => $customerName, 'email' => $customerEmail, 'mobile' => $customerPhone, 'webhookUrl' => $webhookUrl
  ]);
  $base64UrlHeader = base64UrlEncode($header);
  $base64UrlPayload = base64UrlEncode($payload);
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
  $base64UrlSignature = base64UrlEncode($signature);
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
  $cURLConnection = curl_init();
  curl_setopt($cURLConnection, CURLOPT_URL, $withdrawUrl);

  $headr = array();
  $headr[] = 'Content-length: 0';
  $headr[] = 'Content-type: application/json';
  $headr[] = 'Authorization: ' . $jwt;
  $headr[] = 'Access-Control-Allow-Origin: ' . $_SERVER['SERVER_NAME'];
  $agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';
  curl_setopt($cURLConnection, CURLOPT_USERAGENT, $agent);
  curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
  curl_setopt($cURLConnection, CURLOPT_POST, true);
  curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($cURLConnection);
  $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
  if (curl_errno($cURLConnection)) {
    echo $_SERVER['SERVER_NAME'];
    echo 'Request Error:' . curl_error($cURLConnection);
  } else {
    if ($httpcode == 302 || $httpcode == 301) {
      $url = str_replace("Found. Redirecting to ", "", $response);
      $url = trim($url);
      header("Location: " . $url);
    } else {
      return $response;
    }
  }

  curl_close($cURLConnection);
}

function whitelistAddress($address, $type, $customerId, $customerName, $customerEmail, $customerPhone, $minCoins, $maxCoins)
{
  $secret  = "jj/Ix1eg0JCltEyFcUtG6A==";
  $whitelistUrl = "https://customerservice.walletpayment.net/api/v1/whitelist";
  $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
  $payload = json_encode([
    'iss' => 'walletpayment.net', 'exp' => strtotime("+7 minutes"), 'address' => $address, 'type' => $type,  'customerId' => $customerId, 'customerName' => $customerName, 'customerEmail' => $customerEmail, 'customerPhone' => $customerPhone, 'minCoins' => $minCoins, 'maxCoins' => $maxCoins
  ]);
  $base64UrlHeader = base64UrlEncode($header);
  $base64UrlPayload = base64UrlEncode($payload);
  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
  $base64UrlSignature = base64UrlEncode($signature);
  $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

  $cURLConnection = curl_init();
  curl_setopt($cURLConnection, CURLOPT_URL, $whitelistUrl);

  $headr = array();
  $headr[] = 'Content-length: 0';
  $headr[] = 'Content-type: application/json';
  $headr[] = 'Authorization: ' . $jwt;
  $headr[] = 'Access-Control-Allow-Origin: ' . $_SERVER['SERVER_NAME'];
  $agent = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; pl; rv:1.9) Gecko/2008052906 Firefox/3.0';
  curl_setopt($cURLConnection, CURLOPT_USERAGENT, $agent);
  curl_setopt($cURLConnection, CURLOPT_HTTPHEADER, $headr);
  curl_setopt($cURLConnection, CURLOPT_POST, true);
  curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);
  $response = curl_exec($cURLConnection);
  $httpcode = curl_getinfo($cURLConnection, CURLINFO_HTTP_CODE);
  curl_close($cURLConnection);
  if (curl_errno($cURLConnection)) {
    return false;
  } else {
    if ($httpcode == 200) {
      return true;
    } else {
      return false;
    }
  }
}

function validateResult($jwt)
{
  $secret  = "jj/Ix1eg0JCltEyFcUtG6A==";
  $tokenParts = explode('.', $jwt);
  $header = base64_decode($tokenParts[0]);
  $payload = base64_decode($tokenParts[1]);
  $signatureProvided = $tokenParts[2];
  
  $base64UrlHeader = base64UrlEncode($header);
  $base64UrlPayload = base64UrlEncode($payload);  

  $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
  $signatureCreated = base64UrlEncode($signature);

  return $signatureCreated == $signatureProvided;
}

function getResponseResult($token)
{
  $tokenParts = explode(".", $token);  
  $tokenPayload = base64_decode($tokenParts[1]);
  $jwtPayload = json_decode($tokenPayload);
  return $jwtPayload;
}


//sendPaymentDeposit(uniqid(), 4, "TRX", "TJNfvCLjqn4QKuFYVDJZiWzyHqfHrkRfAN", "SS0001", "Suresh", "mr.suresh89@gmail.com", "+919791091935", "https://crowdnclub.com/payment_success.php", "https://crowdnclub.com/payment_failure.php", "https://crowdnclub.com/deposit_webhook.php");
//sendPaymentWithdraw(uniqid(), 4, "TRX", "TJNfvCLjqn4QKuFYVDJZiWzyHqfHrkRfAN", "SS0001", "Suresh", "mr.suresh89@gmail.com", "+919791091935", "https://crowdnclub.com/withdraw_webhook.php");
//whitelistAddress("TJNfvCLjqn4QKuFYVDJZiWzyHqfHrkRfAT", "TRX", "SS0001", "Suresh", "mr.suresh89@gmail.com", "+919791091935", 1, 100);
//validateResult("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ3YWxsZXRwYXltZW50Lm5ldCIsImV4cCI6MTY2NTE0NjIxNSwidHhuaWQiOiI2MzQwMWJjMzYzZjYzIiwiYW1vdW50Ijo0LCJ0eXBlIjoiVFJYIiwiYWRkcmVzcyI6IlRKTmZ2Q0xqcW40UUt1RllWREpaaVd6eUhxZkhya1JmQU4iLCJjaWQiOiJTUzAwMDEiLCJuYW1lIjoiU3VyZXNoIiwiZW1haWwiOiJtci5zdXJlc2g4OUBnbWFpbC5jb20iLCJtb2JpbGUiOiIrOTE5NzkxMDkxOTM1Iiwic3VjY2Vzc1VybCI6Imh0dHBzOlwvXC9jcm93ZG5jbHViLmNvbVwvcGF5bWVudF9zdWNjZXNzLnBocCIsImZhaWx1cmVVcmwiOiJodHRwczpcL1wvY3Jvd2RuY2x1Yi5jb21cL3BheW1lbnRfZmFpbHVyZS5waHAiLCJ3ZWJob29rVXJsIjoiaHR0cHM6XC9cL2Nyb3dkbmNsdWIuY29tXC9wYXltZW50X2hvb2sucGhwIn0.OVOQS8-HW3es0nI0VP1BFsnjCKnnO34OG899I8fvj00");
//getResponseResult("eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJ3YWxsZXRwYXltZW50Lm5ldCIsImV4cCI6MTY2NTE0NjIxNSwidHhuaWQiOiI2MzQwMWJjMzYzZjYzIiwiYW1vdW50Ijo0LCJ0eXBlIjoiVFJYIiwiYWRkcmVzcyI6IlRKTmZ2Q0xqcW40UUt1RllWREpaaVd6eUhxZkhya1JmQU4iLCJjaWQiOiJTUzAwMDEiLCJuYW1lIjoiU3VyZXNoIiwiZW1haWwiOiJtci5zdXJlc2g4OUBnbWFpbC5jb20iLCJtb2JpbGUiOiIrOTE5NzkxMDkxOTM1Iiwic3VjY2Vzc1VybCI6Imh0dHBzOlwvXC9jcm93ZG5jbHViLmNvbVwvcGF5bWVudF9zdWNjZXNzLnBocCIsImZhaWx1cmVVcmwiOiJodHRwczpcL1wvY3Jvd2RuY2x1Yi5jb21cL3BheW1lbnRfZmFpbHVyZS5waHAiLCJ3ZWJob29rVXJsIjoiaHR0cHM6XC9cL2Nyb3dkbmNsdWIuY29tXC9wYXltZW50X2hvb2sucGhwIn0.OVOQS8-HW3es0nI0VP1BFsnjCKnnO34OG899I8fvj00");

