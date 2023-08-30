<?php
 include_once("connection.php");
if(!empty($_FILES['file_attachment']['name']))
  {
    $t=time();
    $fileName = $_FILES["file_attachment"]["name"].$t;
    $target_dir = "uploads/";
    if (!file_exists($target_dir))
    {
      mkdir($target_dir, 0777);
    }
    $newfilename= date('dmYHis').str_replace(" ", "", basename($_FILES["file_attachment"]["name"]));
    // $target_file =
    //   $target_dir . basename($_FILES["file_attachment"]["name"]);
    $target_file =
    $target_dir . $newfilename;
    $imageFileType = 
    strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if file already exists
    if (file_exists($target_file)) {
      echo json_encode(
         array(
           "status" => 0,
           "data" => array()
           ,"msg" => "Sorry, file already exists."
         )
      );
      die();
    }
    // Check file size
    if ($_FILES["file_attachment"]["size"] > 50000000) {
      echo json_encode(
         array(
           "status" => 0,
           "data" => array(),
           "msg" => "Sorry, your file is too large."
         )
       );
      die();
    }
  
    if (
      move_uploaded_file(
        $_FILES["file_attachment"]["tmp_name"], $target_file
      )
    ) {

      $userId= $_POST['userId'];
      $docType= $_POST['docType'];

      $documentFrontImage= $_FILES["file_attachment"]["name"];
      $query = "UPDATE customer SET accountVetified = '0',varificationType = 'm', documentFrontImage = '$newfilename', documentName = '$docType' where id = '$userId'";
      $result2 = mysqli_query($con, $query);
       
  
      if ($result2 > 0) {
        echo json_encode(
          array(
            "status" => 1,
            "filePath" => $newfilename,
            "data" => array(),
            "msg" => "The file " . 
                     basename($_FILES["file_attachment"]["name"]) .
                     " has been uploaded."));
          // return array('flag' => 1);
      } else {
        echo json_encode(
          array(
            "status" => 0,
            "data" => array(),
            "msg" => "error while updating data"));
      }

      
    } else {
      echo json_encode(
        array(
          "status" => 0,
          "data" => array(),
          "msg" => "Sorry, there was an error uploading your file."
        )
      );
    }
  }
?>