<?php
ini_set("display_errors", 1);
    ini_set("track_errors", 1);
    ini_set("html_errors", 1);
    error_reporting(E_ALL);
// $processed = FALSE;
// $ERROR_MESSAGE = '';

// // ************* Call API:
// $ch = curl_init();
// curl_setopt($ch, CURLOPT_URL, "https://myrc.in/plan_api/mobile_normal_plan?username=503142&token=9de2e6e3de7d7ea54e6c67c0d6976b22&opcode=A");
// curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
// curl_setopt($ch, CURLOPT_POSTFIELDS,"username=myname&password=mypass");   // post data
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
// $json = curl_exec($ch);
// curl_close ($ch);

// // returned json string will look like this: {"code":1,"data":"OK"}
// // "code" may contain an error code and "data" may contain error string instead of "OK"
// $obj = json_decode($json);

// if ($obj->{'code'} == '1')
// {
//   $processed = TRUE;
// }else{
//   $ERROR_MESSAGE = $obj->{'data'};
// }

// ...

// if (!$processed && $ERROR_MESSAGE != '') {
//     echo $ERROR_MESSAGE;
// }

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://myrc.in/plan_api/mobile_normal_plan?username=503142&token=9de2e6e3de7d7ea54e6c67c0d6976b22&opcode=A",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
echo $response;
curl_close($curl);


?>