<?php
ini_set("display_errors", 1);
    ini_set("track_errors", 1);
    ini_set("html_errors", 1);
    error_reporting(E_ALL);
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once("connection.php");

// check email existence here

    // get posted data
    $data = json_decode(file_get_contents("php://input"));

    $q_get_customer_detail = "SELECT * from tbl_settings";
    $query_get_customer_detail = mysqli_query($con, $q_get_customer_detail);

    $q_get_total_investment = "SELECT sum(investment_wallet) as totalInvestment FROM `customer`";
    $query_get_total_investment = mysqli_query($con, $q_get_total_investment);
    $totalInvestment = 0;
    while($row2 = mysqli_fetch_assoc($query_get_total_investment)) {  
        $totalInvestment = $row2['totalInvestment'];
    }
    if (mysqli_num_rows($query_get_customer_detail) > 0) {
        $employeeArr = array();
        $employeeArr["status"] = "success";
        $employeeArr["totalInvestment"] = $totalInvestment;
        while($row = mysqli_fetch_assoc($query_get_customer_detail)) {            
            $employeeArr["investmentMaximumValue"] = $row['investmentMaximumValue'];
        }      
        http_response_code(200);
        echo json_encode($employeeArr);
    }else{
        http_response_code(404);
        array("message" => "No record found.", "status" => "failur");
    }

  
?>