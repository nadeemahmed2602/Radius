<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "transection";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    public function getWalletBalance(){
        $sqlQuery = "SELECT wallet_amount,utilityWallet,lockedWallet,investment_wallet,earnings_wallet,usdt_wallet FROM customer WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind given email value
        $stmt->bindParam(1, $this->user_id);
        
        $stmt->execute();
        return $stmt;
    }

    public function create(){
  
             $sqlQuery = "INSERT INTO " . $this->table_name . " SET
                                                                    user_id= :user_id,
                                                                    amount= :amount,
                                                                    date= :payment_date,
                                                                    time= :payment_time,
                                                                    status= :status,
                                                                    payment_for= :payment_for,
                                                                    payment_status= :payment_status,
                                                                    transactionType= :transactionType,
                                                                    walletBalance= :walletBalance";
        
            $stmt = $this->conn->prepare($sqlQuery);
            $sqlQuery1 = "SELECT wallet_amount FROM customer WHERE id = ?";
            $stmt1 = $this->conn->prepare($sqlQuery1);
            $stmt1->bindParam(1, $this->user_id);
            $stmt1->execute();
            $itemCount1 = $stmt1->rowCount();
            $amount = 0;
            if($itemCount1 > 0){
                while ($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)){
                    $amount = $row1['wallet_amount'];
                }
            }
            // echo "id:".$this->user_id;
            
            // echo "Amount:".$amount;
        
 
            // sanitize
            $this->user_id=htmlspecialchars(strip_tags($this->user_id));
            $this->amount=htmlspecialchars(strip_tags($this->amount));
            $this->status=htmlspecialchars(strip_tags($this->status));
            $this->payment_for=htmlspecialchars(strip_tags($this->payment_for));
            $this->payment_status=htmlspecialchars(strip_tags($this->payment_status));
            $this->payment_date=htmlspecialchars(strip_tags($this->payment_date));
            $this->payment_time=htmlspecialchars(strip_tags($this->payment_time));
            $this->transactionType=htmlspecialchars("0");
            $this->walletBalance=htmlspecialchars(strip_tags($amount));
  

            //  echo "HHHH:".$this->user_id;
        
            // bind data
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":amount", $this->amount);
            $stmt->bindParam(":status", $this->status);
            $stmt->bindParam(":payment_for", $this->payment_for);
            $stmt->bindParam(":payment_status", $this->payment_status);
            $stmt->bindParam(":payment_date", $this->payment_date);
            $stmt->bindParam(":payment_time", $this->payment_time);
            $stmt->bindParam(":transactionType", $this->transactionType);
            $stmt->bindParam(":walletBalance", $this->walletBalance);
            
        
            if($stmt->execute()){
               return true;
            }
            return false;
    }
        
        public function UpdateWallet(){
            $sqlQuery = "UPDATE customer SET wallet_amount= :updated_balance where id= :user_id";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->user_id=htmlspecialchars(strip_tags($this->user_id));
            $this->updated_balance=htmlspecialchars(strip_tags($this->updated_balance));
            
            // bind data
            $stmt->bindParam(":user_id", $this->user_id);
            $stmt->bindParam(":updated_balance", $this->updated_balance);
            
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

        public function UpdateUtilityWallet(){
           $sqlQuery = "UPDATE customer SET utilityWallet= :updated_balance_utility, wallet_amount= :updated_balance where id= :user_id";
       
           $stmt = $this->conn->prepare($sqlQuery);
       
           $this->user_id=htmlspecialchars(strip_tags($this->user_id));
           $this->updated_balance=htmlspecialchars(strip_tags($this->updated_balance));
           $this->updated_balance_utility=htmlspecialchars(strip_tags($this->updated_balance_utility));
       
           // bind data
           $stmt->bindParam(":user_id", $this->user_id);
           $stmt->bindParam(":updated_balance", $this->updated_balance);
           $stmt->bindParam(":updated_balance_utility", $this->updated_balance_utility);
       
           if($stmt->execute()){
              return true;
           }
           return false;
       }    
       
       public function UpdateOnlyUtilityWallet(){
        $sqlQuery = "UPDATE customer SET utilityWallet= :updated_balance_utility where id= :user_id";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->updated_balance_utility=htmlspecialchars(strip_tags($this->updated_balance_utility));
    
        // bind data
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":updated_balance_utility", $this->updated_balance_utility);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }    

     public function UpdateSenderRecieverWalletAfterInternal(){
        // update sender's balance ( debit )
        $sqlQuerySender = "UPDATE customer SET wallet_amount= :updated_balance_sender where id= :sender_id";
        // update reciever's balance ( credit )
        $sqlQueryReciever = "UPDATE customer SET wallet_amount= :updated_balance_reciever where id= :reciever_id";

        $stmtSender = $this->conn->prepare($sqlQuerySender);
        $stmtReciever = $this->conn->prepare($sqlQueryReciever);

    
        $this->sender_id=htmlspecialchars(strip_tags($this->sender_id));
        $this->updated_balance_sender=htmlspecialchars(strip_tags($this->updated_balance_sender));

        $this->reciever=htmlspecialchars(strip_tags($this->reciever_id));
        $this->updated_balance_reciever=htmlspecialchars(strip_tags($this->updated_balance_reciever));
        
        // bind data
        $stmtSender->bindParam(":sender_id", $this->sender_id);
        $stmtSender->bindParam(":updated_balance_sender", $this->updated_balance_sender);

        $stmtReciever->bindParam(":reciever_id", $this->reciever_id);
        $stmtReciever->bindParam(":updated_balance_reciever", $this->updated_balance_reciever);
        
    
        if($stmtSender->execute() && $stmtReciever->execute()){
           return true;
        }
        return false;
    }

}