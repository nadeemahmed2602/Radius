<?php
 include_once("connection.php");
      $userId= $_POST['userId'];
      $docType= $_POST['docType'];
      $documentNumber= $_POST['documentNumber'];
      $q_checkDocumentNumberExist = "SELECT * from customer where documentNumber='$documentNumber'";
      $result_checkDocumentNumberExist = mysqli_query($con, $q_checkDocumentNumberExist);
      $records=mysqli_num_rows($result_checkDocumentNumberExist);
      if($records <= 0){
        $query = "UPDATE customer SET accountVetified = '2',varificationType = 'm', documentName = '$docType', documentNumber = '$documentNumber' where id = '$userId'";
        $result2 = mysqli_query($con, $query);
        if ($result2 > 0) {
          echo json_encode(
            array(
              "status" => 1,
              "data" => array(),
              "msg" => "Your document is submitted and Review is in process."));
            // return array('flag' => 1);
        } else {
          echo json_encode(
            array(
              "status" => 0,
              "data" => array(),
              "msg" => "error while submiting KYC Details"));
        }
      }else{
        echo json_encode(
          array(
            "status" => 0,
            "data" => array(),
            "msg" => "This document is already used ! try with different document number"));
      }
      
?>