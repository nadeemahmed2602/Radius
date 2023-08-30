<?php
// 'user' object
require_once './vendor/autoload.php';
use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "customer";
 
   
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
 
// create new user record
    function create(){
        // insert query
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    name = :name,
                    email = :email,
                    phone = :phone,
                    otp = :otp,
                    parent_id = :parent_id";
    
        // prepare the query
        $stmt = $this->conn->prepare($query);
    
        // sanitize
        $this->name=htmlspecialchars(strip_tags($this->name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->parent_id=htmlspecialchars(strip_tags($this->parent_id));

        // bind the values
        $stmt->bindParam(':name', $this->name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':phone', $this->phone);

        // hash the password before saving to database
        $stmt->bindParam(':otp', $this->otp);
        $stmt->bindParam(':parent_id', $this->parent_id);

    
        // execute the query, also check if query was successful
        echo "HHHH";
        if($stmt->execute()){
            return true;
        }
    
        return false;
    }
 
// emailExists() method will be here 
 
 
public function getData(){
    $sqlQuery = "SELECT * FROM " . $this->table_name . "";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
}
// check if given email exist in the database
function emailExists(){

	// query to check if email exists
	$query = "SELECT *
			FROM " . $this->table_name . "
			WHERE email = ?
			LIMIT 0,1";

	// prepare the query
	$stmt = $this->conn->prepare( $query );

	// sanitize
	$this->email=htmlspecialchars(strip_tags($this->email));

	// bind given email value
	$stmt->bindParam(1, $this->email);

	// execute the query
	$stmt->execute();

	// get number of rows
	$num = $stmt->rowCount();

	// if email exists, assign values to object properties for easy access and use for php sessions
	if($num>0){

		// get record details / values
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->password = $row['password'];
		$this->phone = $row['phone'];
		$this->email = $row['email'];
		$this->wallet_amount = $row['wallet_amount'];

		// return true because email exists in the database
		return true;
	}

	// return false if email does not exist in the database
	return false;
}

function verifyOldMpin(){
    
    // query to check if email exists
    $mpin = htmlspecialchars(strip_tags($this->mpin));
    $query = "SELECT *
            FROM customer
            WHERE id = ?";

    // prepare the query
    $stmt = $this->conn->prepare( $query );

    // sanitize
    $this->id=htmlspecialchars(strip_tags($this->id));

    // bind given email value
    $stmt->bindParam(1, $this->id);

    // execute the query
    $stmt->execute();

    // get number of rows
    $num = $stmt->rowCount();

    // if email exists, assign values to object properties for easy access and use for php sessions
    if($num>0){

        // get record details / values
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // assign values to object properties
        $mpinFromDb = $row['mpin'];
        if($mpinFromDb == $mpin){
            return true;
        }else{
            return false;
        }
     
    }

    return false;

}

function phoneExists(){

	// query to check if email exists
	$query = "SELECT *
			FROM " . $this->table_name . "
			WHERE phone = ?
			LIMIT 0,1";

	// prepare the query
	$stmt = $this->conn->prepare( $query );

	// sanitize
	$this->phone=htmlspecialchars(strip_tags($this->phone));

	// bind given email value
	$stmt->bindParam(1, $this->phone);

	// execute the query
	$stmt->execute();

	// get number of rows
	$num = $stmt->rowCount();

	// if email exists, assign values to object properties for easy access and use for php sessions
	if($num>0){

		// get record details / values
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->password = $row['password'];
		$this->phone = $row['phone'];
		$this->wallet_amount = $row['wallet_amount'];
	

		// return true because email exists in the database
		return true;
	}

	// return false if email does not exist in the database
	return false;
}

function loginWithMpin(){

	// query to check if email exists
	$query = "SELECT *
			FROM " . $this->table_name . "
			WHERE phone = ? and mpin = ?
			LIMIT 0,1";

	// prepare the query
	$stmt = $this->conn->prepare( $query );

	// sanitize
	$this->phone=htmlspecialchars(strip_tags($this->phone));
    $this->mpin=htmlspecialchars(strip_tags($this->mpin));

	// bind given email value
	$stmt->bindParam(1, $this->phone);
	$stmt->bindParam(2, $this->mpin);

	// execute the query
	$stmt->execute();

	// get number of rows
	$num = $stmt->rowCount();

	// if email exists, assign values to object properties for easy access and use for php sessions
	if($num>0){

		// get record details / values
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->id = $row['id'];
		$this->name = $row['name'];
		$this->email = $row['email'];
		$this->password = $row['password'];
		$this->phone = $row['phone'];
		$this->wallet_amount = $row['wallet_amount'];
	

		// return true because email exists in the database
		return true;
	}

	// return false if email does not exist in the database
	return false;
}

 # Get Customer By Phone
    function getCustomerDataByPhone(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $stmt->bindParam(1, $this->phone);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->wallet_amount = $row['wallet_amount'];
            return $row;
        }else{
            return null;
        }
    }

function otpVerify(){

	// query to check if email exists
	$query = "SELECT *
			FROM " . $this->table_name . " WHERE email = :email and otp = :otp";

	// prepare the query
	$stmt = $this->conn->prepare( $query );

	// sanitize
	$this->otp=htmlspecialchars(strip_tags($this->otp));
    $this->email=htmlspecialchars(strip_tags($this->email));

    // bind data
    $stmt->bindParam(":otp", $this->otp);
    $stmt->bindParam(":email", $this->email);

	// execute the query
	$stmt->execute();

	// get number of rows
	$num = $stmt->rowCount();

	if($num>0){
		return true;
	}

	// return false if email does not exist in the database
	return false;
}


public function updateInfo(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        otp= :otp WHERE email = :email";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->otp=htmlspecialchars(strip_tags($this->otp));
            $this->email=htmlspecialchars(strip_tags($this->email));
        
            // bind data
            $stmt->bindParam(":otp", $this->otp);
            $stmt->bindParam(":email", $this->email);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        
        
public function updateToken(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        token= :token WHERE phone = :phone";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->token=htmlspecialchars(strip_tags($this->token));
            $this->phone=htmlspecialchars(strip_tags($this->phone));
        
            // bind data
            $stmt->bindParam(":token", $this->token);
            $stmt->bindParam(":phone", $this->phone);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        
        
public function updatePassword(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        password= :passwords WHERE email = :email";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->passwords=htmlspecialchars(strip_tags($this->passwords));
            $this->email=htmlspecialchars(strip_tags($this->email));
        
            // bind data
            $stmt->bindParam(":email", $this->email);
            $password_hash = password_hash($this->passwords, PASSWORD_BCRYPT);
            $stmt->bindParam(':passwords', $password_hash);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        
        
        public function changePassword(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        password= :newpassword WHERE phone = :phone";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->newpassword=htmlspecialchars(strip_tags($this->newpassword));
            $this->phone=htmlspecialchars(strip_tags($this->phone));
        
            // bind data
            $stmt->bindParam(":phone", $this->phone);
            $password_hash = password_hash($this->newpassword, PASSWORD_BCRYPT);
            $stmt->bindParam(':newpassword', $password_hash);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
        
          public function getInfo(){
        $sqlQuery = "SELECT `wallet_amount`,`utilityWallet`,`lockedWallet`, `investment_wallet`, `earnings_wallet`, `usdt_wallet` FROM ". $this->table_name ." WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->id=htmlspecialchars(strip_tags($this->id));
    
    	// bind given email value
    	$stmt->bindParam(1, $this->id);
        
        $stmt->execute();
        return $stmt;
    }
        
        
        public function changeMpin(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        mpin= :mpin WHERE id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->mpin=htmlspecialchars(strip_tags($this->mpin));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":mpin", $this->mpin);
            $stmt->bindParam(':id', $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }
    
    # Get Customer By Phone
    function getCustomerByPhone(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $stmt->bindParam(1, $this->phone);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->wallet_amount = $row['wallet_amount'];
            return true;
        }
        return false;
    }

    # Check Is Phone Verified
    function checkIsPhoneVerified(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $stmt->bindParam(1, $this->phone);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['mobile_verified_at']) && $row['mobile_verified_at'] != ''){
                return true;
            }else{
                return false;
            }
        }
    }

    # Update OTP
    public function updateOTP(){
        $sqlQuery = "UPDATE ". $this->table_name ." SET otp= :otp WHERE phone = :phone";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->phone=htmlspecialchars(strip_tags($this->phone));

        $stmt->bindParam(":otp", $this->otp);
        $stmt->bindParam(":phone", $this->phone);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }

     # Update userInfo
     public function updateUserInfo($name){
        $sqlQuery = "UPDATE ". $this->table_name ." SET name= :name, email= :email WHERE phone = :phone";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->name=htmlspecialchars(strip_tags($name));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    #update mpin
    public function updateMpin(){
        $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                    mpin= :mpin WHERE phone = :phone";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        // sanitize
        $this->mpin=htmlspecialchars(strip_tags($this->mpin));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
    
        // bind data
        $stmt->bindParam(":mpin", $this->mpin);
        $stmt->bindParam(":phone", $this->phone);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    # OTP Send
    public function sendOTP(){
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->Fast2SmsApiKey=htmlspecialchars(strip_tags($this->Fast2SmsApiKey));

        $message = "Your one time password (OTP) to register is " . $this->otp . ".";

        $fields = array(
            "message" => $message,
            "language" => "english",
            "route" => "q",
            "numbers" => $this->phone,
        );

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: ".$this->Fast2SmsApiKey,
            "accept: */*",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return false;
        }
        // echo $response;
        return true;
    }

    public function sendOTPForgetPass(){
  
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->Fast2SmsApiKey=htmlspecialchars(strip_tags($this->Fast2SmsApiKey));

        $message = "Your one time OTP to forgot password is " . $this->otp . ".";

        $fields = array(
            "message" => $message,
            "language" => "english",
            "route" => "q",
            "numbers" => $this->phone,
        );
        //   echo "HEllo";
        //   echo $this->phone;

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => "https://www.fast2sms.com/dev/bulkV2",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYHOST => 0,
        CURLOPT_SSL_VERIFYPEER => 0,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
            "authorization: ".$this->Fast2SmsApiKey,
            "accept: */*",
            "cache-control: no-cache",
            "content-type: application/json"
        ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            // echo "cURL Error #:" . $err;
            return false;
        }
        // echo $response;
        return true;
    }

# OTP Verification
    function otpVerification(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE phone = :phone and otp = :otp";
        $stmt = $this->conn->prepare( $query );

        $this->phone=htmlspecialchars(strip_tags($this->phone));
        $this->otp=htmlspecialchars(strip_tags($this->otp));

        date_default_timezone_set("Asia/Kolkata");
        $this->mobile_verified_at = date('Y-m-d H:i:s', time());

        $stmt->bindParam(":otp", $this->otp);
        $stmt->bindParam(":phone", $this->phone);

        $stmt->execute();
        $num = $stmt->rowCount();

        if($num>0){
            $sqlQuery = "UPDATE ". $this->table_name ." SET mobile_verified_at= :mobile_verified_at, otp= NULL WHERE phone = :phone";
            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(":mobile_verified_at", $this->mobile_verified_at);
            $stmt->bindParam(":phone", $this->phone);
        
            $stmt->execute();

            return true;
        }
        return false;
    }

     # OTP Verification
     function otpVerificationEmail(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = :email and otp = :otp";
        $stmt = $this->conn->prepare( $query );

        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->otp=htmlspecialchars(strip_tags($this->otp));

        date_default_timezone_set("Asia/Kolkata");
        $this->mobile_verified_at = date('Y-m-d H:i:s', time());

        $stmt->bindParam(":otp", $this->otp);
        $stmt->bindParam(":email", $this->email);

        $stmt->execute();
        $num = $stmt->rowCount();

        if($num>0){
            $sqlQuery = "UPDATE ". $this->table_name ." SET mobile_verified_at= :mobile_verified_at, otp= NULL WHERE email = :email";
            $stmt = $this->conn->prepare($sqlQuery);

            $stmt->bindParam(":mobile_verified_at", $this->mobile_verified_at);
            $stmt->bindParam(":email", $this->email);
        
            $stmt->execute();

            return true;
        }
        return false;
    }

     # Get Customer By email
     function getCustomerByEmail(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->email=htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->wallet_amount = $row['wallet_amount'];
            return true;
        }
        return false;
    }

    public function sendOTPForgetPassEmail(){
  
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $message = "Your one time OTP to forgot password is " . $this->otp . ".";
        $mail = new PHPMailer(true);
        try {
            
            $mail->SMTPDebug = 2;									
            $mail->isSMTP();											
            $mail->Host	 = 'radiuschain.net';					
            $mail->SMTPAuth = true;							
            $mail->Username = 'no-reply@radiuschain.net';				
            $mail->Password = 'aYL9&pd4WYeV';						
            $mail->SMTPSecure = 'tls';							
            $mail->Port	 = 587;
            $mail->setFrom('no-reply@radiuschain.net', 'Radius App');
            $mail->addAddress($this->email, 'Name');                     
            $mail->isHTML(true);								
            $mail->Subject = 'OTP verification for Radius App';                       
            $mail->Body = $message;
            $mail->SMTPDebug = 0;
            $mail->AltBody = 'Error';
            $mail->send();
            return true;
        } catch (phpmailerException $e) {
            return false;
            // echo $e;
            // echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            return false;
            // echo $e;
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
      
    } 
    
     # OTP Send
     public function sendOTPOnMail(){
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->email=htmlspecialchars(strip_tags($this->email));
        $message = "Your one time password (OTP) to register is " . $this->otp . ".";
        $mail = new PHPMailer(true);
        try {
            
            $mail->SMTPDebug = 2;									
            $mail->isSMTP();											
            $mail->Host	 = 'radiuschain.net';					
            $mail->SMTPAuth = true;							
            $mail->Username = 'no-reply@radiuschain.net';				
            $mail->Password = 'aYL9&pd4WYeV';						
            $mail->SMTPSecure = 'tls';							
            $mail->Port	 = 587;
            $mail->setFrom('no-reply@radiuschain.net', 'Radius App');
            $mail->addAddress($this->email, 'Name');                     
            $mail->isHTML(true);								
            $mail->Subject = 'OTP verification for Radius App';                       
            $mail->Body = $message;
            $mail->SMTPDebug = 0;
            $mail->AltBody = 'Error';
            $mail->send();
            return true;
        } catch (phpmailerException $e) {
            return false;
            // echo $e;
            // echo $e->errorMessage(); //Pretty error messages from PHPMailer
        } catch (Exception $e) {
            return false;
            // echo $e;
            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

    function loginWithMpinEmail(){

        // query to check if email exists
        $query = "SELECT *
                FROM " . $this->table_name . "
                WHERE email = ? and mpin = ?
                LIMIT 0,1";
    
        // prepare the query
        $stmt = $this->conn->prepare( $query );
    
        // sanitize
        $this->email=htmlspecialchars(strip_tags($this->email));
        $this->mpin=htmlspecialchars(strip_tags($this->mpin));
    
        // bind given email value
        $stmt->bindParam(1, $this->email);
        $stmt->bindParam(2, $this->mpin);
    
        // execute the query
        $stmt->execute();
    
        // get number of rows
        $num = $stmt->rowCount();
    
        // if email exists, assign values to object properties for easy access and use for php sessions
        if($num>0){
    
            // get record details / values
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
            // assign values to object properties
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->wallet_amount = $row['wallet_amount'];
        
    
            // return true because email exists in the database
            return true;
        }
    
        // return false if email does not exist in the database
        return false;
    }
    
     # Get Customer By Phone
     function getCustomerDataByEmail(){
        $query = "SELECT * FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";
        $stmt = $this->conn->prepare( $query );
        $this->email=htmlspecialchars(strip_tags($this->email));
        $stmt->bindParam(1, $this->email);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $this->id = $row['id'];
            $this->name = $row['name'];
            $this->password = $row['password'];
            $this->phone = $row['phone'];
            $this->email = $row['email'];
            $this->wallet_amount = $row['wallet_amount'];
            return $row;
        }else{
            return null;
        }
    }

      # Update OTP
      public function updateOTPEmail(){
        $sqlQuery = "UPDATE ". $this->table_name ." SET otp= :otp WHERE email = :email";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->otp=htmlspecialchars(strip_tags($this->otp));
        $this->email=htmlspecialchars(strip_tags($this->email));

        $stmt->bindParam(":otp", $this->otp);
        $stmt->bindParam(":email", $this->email);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }

      # Update userInfo
      public function updateUserInfoEmail($name,$email,$phone){
        $sqlQuery = "UPDATE ". $this->table_name ." SET name= :name, phone= :phone WHERE email = :email";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->name=htmlspecialchars(strip_tags($name));
        $this->email=htmlspecialchars(strip_tags($email));
        $this->phone=htmlspecialchars(strip_tags($phone));
        
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":phone", $this->phone);
    
        if($stmt->execute()){
            return true;
        }
        return false;
    }

      #update mpin
      public function updateMpinEmail(){
        $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                    mpin= :mpin WHERE email = :email";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        // sanitize
        $this->mpin=htmlspecialchars(strip_tags($this->mpin));
        $this->email=htmlspecialchars(strip_tags($this->email));
    
        // bind data
        $stmt->bindParam(":mpin", $this->mpin);
        $stmt->bindParam(":email", $this->email);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }
}