<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// database connection will be here
// database connection will be here
// session_start();
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/cart_one.php';

include_once 'config/core.php';

require_once './vendor/autoload.php';

use Twilio\Rest\Client;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// get database connection
$database = new Database();
$db = $database->getConnection();

// instantiate user object
$user = new User($db);

$data = json_decode(file_get_contents("php://input"));


                        $mail = new PHPMailer(true);
                        try {
                            
                            $mail->SMTPDebug = 2;									
													$mail->isSMTP();											
													$mail->Host	 = 'coinconnect.app';					
													$mail->SMTPAuth = true;							
													$mail->Username = 'vrushali@coinconnect.app';				
													$mail->Password = 'W.XoGy24dBLL';						
													$mail->SMTPSecure = 'tls';							
													$mail->Port	 = 587;
											
													$mail->setFrom('vrushali@coinconnect.app', 'Name');
												// 	$mail->addAddress('vrushali@coinconnect.app');
													$mail->addAddress('sonivrushali1234@gmail.com', 'Name');
													
													$mail->isHTML(true);								
													$mail->Subject = 'form subject';
													
													// $mail->Body = 'HTML message body in <b>bold</b> ';
													$mail->Body = "Hello";
													$mail->SMTPDebug = 0;
													$mail->AltBody = 'Body in plain text for non-HTML mail clients';
													echo $mail->send();
													
                        //     $mail->isSMTP();                            //Send using SMTP
                        //     $mail->Host       = "coinconnect.app";              //Set the SMTP server to send through
                        //     $mail->SMTPAuth   = true;                   //Enable SMTP authentication
                        //     $mail->Username   = $smtpUsername;          //SMTP username
                        //     $mail->Password   = $smtpPassword;          //SMTP password
                        //     $mail->SMTPSecure = $smtpSecure;            //Enable implicit TLS encryption
                        //     $mail->Port       = $smtpPort;              //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

                        //     //Recipients
                        //     $mail->setFrom($setFromEmailAddress);
                        //     $mail->addAddress("sonivrushali1234@gmail.com");          //Add a recipient
                        
                        //     // Customer Email Content
                        //     $mail->isHTML(true);                       //Set email format to HTML
                        //     $mail->Subject = 'Your Order Placed';
                        //     $mail->Body = "Hello";
                        
                        //   echo $mail->send();

                        //     // Clear All Recipients 
                        //     $mail->ClearAllRecipients();
                        //     echo "hello";

                        //     // if(!$mail->send()) {
                        //     //     echo 'Message could not be sent.';
                        //     //     echo 'Mailer Error: ' . $mail->ErrorInfo;
                        //     // } else {
                        //     //     echo 'Message has been sent';
                        //     // }
                        } catch (phpmailerException $e) {
                            echo $e;
                            // echo $e->errorMessage(); //Pretty error messages from PHPMailer
                        } catch (Exception $e) {
                            echo $e;
                            // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                        }
                     
?>