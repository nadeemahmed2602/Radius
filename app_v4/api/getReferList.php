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
    $parent_id = $data->parent_id;
    
    $allChildArray = array();

    # Get Child By Id
    function getChildById($conn, $id){
        global $allChildArray;      
        $sql = "SELECT `child_id` FROM `referral` WHERE `parent_id` = $id";
        $query = mysqli_query($conn, $sql);
        if (mysqli_num_rows($query) > 0) {
            while($row = mysqli_fetch_assoc($query)) {            
                $allChildArray[] = $row['child_id'];
                getChildById($conn, $row['child_id']);
            }               
        }
    }

    getChildById($con, $parent_id);
    $childIDs = implode(', ', $allChildArray);
    $q_get_customer_detail = "SELECT * from customer where id in (".$childIDs.")";
    $query_get_customer_detail = mysqli_query($con, $q_get_customer_detail);
    if (mysqli_num_rows($query_get_customer_detail) > 0) {
        $employeeArr = array();
        $employeeArr["referList"] = array();
        $employeeArr["status"] = "success";
        while($row = mysqli_fetch_assoc($query_get_customer_detail)) {            
            array_push($employeeArr["referList"], $row);
        }      
        http_response_code(200);
        echo json_encode($employeeArr);
    }else{
        http_response_code(404);
        array("message" => "No record found.", "status" => "failur");
    }

  
?>