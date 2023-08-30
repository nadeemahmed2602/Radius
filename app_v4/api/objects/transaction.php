<?php
// 'user' object
class transaction{
 
    // database connection and table name
    private $conn;
    private $table_name = "transaction";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
 
    public $user_id;
    public $amount;
    public $status;
    public $transaction_type;
    public $affected_wallet;
    public $payment_for;
    public $mobile_number;
    public $consumer_number;
    public $operator_code;
    public $flight_no;
    public $wallet_balance;
    public $utility_balance;
    public $investment_balance;
    public $earning_balance;

    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    public function getParent(){
        $sqlQuery = "SELECT * FROM customer where id = ?";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        $stmt->bindParam(1, $this->user_id);

        $stmt->execute();
        return $stmt;
    }

    public function getLevels(){
        $sqlQuery = "SELECT * FROM level_percentage";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getRoiLevels(){
        $sqlQuery = "SELECT * FROM roi_levels_percentage";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    public function getAllUser(){
        $sqlQuery = "SELECT * FROM customer";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }
    

    // public function get
    public function getInvestmentWalletBalance(){
        $sqlQuery = "SELECT wallet_amount,utilityWallet FROM customer WHERE id = ?";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind given email value
        $stmt->bindParam(1, $this->user_id);
        
        $stmt->execute();
        return $stmt;
    }

    public function getData(){
        $sqlQuery = "SELECT * FROM " . $this->table_name . " WHERE user_id = ? order by id desc ";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind given email value
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getRechargeBookingData(){
        $sqlQuery = "SELECT * FROM tbl_booking_recharge_history WHERE userId = ? order by id desc ";
        $stmt = $this->conn->prepare($sqlQuery);
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

        // bind given email value
        $stmt->bindParam(1, $this->user_id);
        $stmt->execute();
        return $stmt;
    }

    public function getIncomePercentage(){
        $sqlQuery = "SELECT * FROM income_percentages";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

    
    public function updateLevelIncome(){
        $arrparentUserId = array();
        $arrparentUserId = $this->parentUserId;
        $sqlQuery = "UPDATE customer SET earnings_wallet = earnings_wallet + :level_income where id IN (" . implode(',', $arrparentUserId) . ")";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->level_income=htmlspecialchars(strip_tags($this->level_income));
    
        // bind data
        $stmt->bindParam(":level_income", $this->level_income);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    public function updateROILevelIncomeSingle(){
        // $arrparentUserId = array();
        // $parentUserId = $this->parentUserId;
        $sqlQuery = "UPDATE customer SET earnings_wallet = earnings_wallet + :level_income where id = :parentUserId";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->level_income=htmlspecialchars(strip_tags($this->level_income));
        $this->parentUserId=htmlspecialchars(strip_tags($this->parentUserId));


    
        // bind data
        $stmt->bindParam(":level_income", $this->level_income);
        $stmt->bindParam(":parentUserId", $this->parentUserId);

    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    public function updateLevelIncomeSingle(){
        // $arrparentUserId = array();
        // $parentUserId = $this->parentUserId;
        $sqlQuery = "UPDATE customer SET usdt_wallet = usdt_wallet + :level_income where id = :parentUserId";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->level_income=htmlspecialchars(strip_tags($this->level_income));
        $this->parentUserId=htmlspecialchars(strip_tags($this->parentUserId));


    
        // bind data
        $stmt->bindParam(":level_income", $this->level_income);
        $stmt->bindParam(":parentUserId", $this->parentUserId);

    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

     public function addROIIncome(){
        // $sqlQuery = "UPDATE customer SET earnings_wallet = earnings_wallet + :roi_income where id= :user_id ";
        $sqlQuery = "UPDATE customer SET investment_wallet= investment_wallet + :investment_wallet_80per, utilityWallet = utilityWallet + :utility_wallet_20per where id= :user_id";

        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->investment_wallet_80per=htmlspecialchars(strip_tags($this->investment_wallet_80per));
        $this->utility_wallet_20per=htmlspecialchars(strip_tags($this->utility_wallet_20per));
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));

    
        // bind data
        $stmt->bindParam(":investment_wallet_80per", $this->investment_wallet_80per);
        $stmt->bindParam(":utility_wallet_20per", $this->utility_wallet_20per);
        $stmt->bindParam(":user_id", $this->user_id);

    
        if($stmt->execute()){
           return true;
        }
        return false;
    }

    
    public function UpdateEarningsWallet(){
        $sqlQuery = "UPDATE customer SET earnings_wallet=  :earnings_wallet where id= :user_id";
    
        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->earnings_wallet=htmlspecialchars(strip_tags($this->earnings_wallet));
    
        // bind data
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":earnings_wallet", $this->earnings_wallet);
    
        if($stmt->execute()){
           return true;
        }
        return false;
    }   
    
    public function UpdateInvestmentWallet(){
        $sqlQuery = "UPDATE customer SET investment_wallet= investment_wallet + :investment_wallet_90per, utilityWallet = utilityWallet + :utility_wallet_10per, wallet_amount= wallet_amount - :amount where id= :user_id";

        $stmt = $this->conn->prepare($sqlQuery);
    
        $this->user_id=htmlspecialchars(strip_tags($this->user_id));
        $this->investment_wallet_90per=htmlspecialchars(strip_tags($this->investment_wallet_90per));
        $this->utility_wallet_10per=htmlspecialchars(strip_tags($this->utility_wallet_10per));
        $this->amount=htmlspecialchars(strip_tags($this->amount));

        // bind data
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":investment_wallet_90per", $this->investment_wallet_90per);
        $stmt->bindParam(":utility_wallet_10per", $this->utility_wallet_10per);
        $stmt->bindParam(":amount", $this->amount);

    
        if($stmt->execute()){
           return true;
        }
        return false;
    }   

    public function create(){
    
                $sqlQuery = "INSERT INTO " . $this->table_name . " SET
                                                                        user_id= :user_id,
                                                                        amount= :amount,
                                                                        status= :status,
                                                                        transaction_type= :transaction_type,
                                                                        affected_wallet= :affected_wallet,
                                                                        payment_for= :payment_for,
                                                                        mobile_number= :mobile_number,
                                                                        consumer_number= :consumer_number,
                                                                        operator_code= :operator_code,
                                                                        flight_no= :flight_no,
                                                                        wallet_balance= :wallet_balance,
                                                                        utility_balance= :utility_balance,
                                                                        investment_balance= :investment_balance,
                                                                        earning_balance= :earning_balance,
                                                                        usdt_wallet= :usdt_wallet";
            


                $stmt = $this->conn->prepare($sqlQuery);

                // sanitize
                $this->user_id=htmlspecialchars(strip_tags($this->user_id));
                $this->amount=htmlspecialchars(strip_tags($this->amount));
                $this->status=htmlspecialchars(strip_tags($this->status));
                $this->transaction_type=htmlspecialchars(strip_tags($this->transaction_type));
                $this->affected_wallet=htmlspecialchars(strip_tags($this->affected_wallet));
                $this->payment_for=htmlspecialchars(strip_tags($this->payment_for));
                $this->mobile_number=htmlspecialchars(strip_tags($this->mobile_number));
                $this->consumer_number=htmlspecialchars(strip_tags($this->consumer_number));
                $this->operator_code=htmlspecialchars(strip_tags($this->operator_code));
                $this->flight_no=htmlspecialchars(strip_tags($this->flight_no));
                $this->wallet_balance=htmlspecialchars(strip_tags($this->wallet_balance));
                $this->utility_balance=htmlspecialchars(strip_tags($this->utility_balance));
                $this->investment_balance=htmlspecialchars(strip_tags($this->investment_balance));
                $this->earning_balance=htmlspecialchars(strip_tags($this->earning_balance)); 
                $this->usdt_wallet=htmlspecialchars(strip_tags($this->usdt_wallet));    
            

                // bind data
                $stmt->bindParam(":user_id", $this->user_id);
                $stmt->bindParam(":amount", $this->amount);
                $stmt->bindParam(":status", $this->status);
                $stmt->bindParam(":transaction_type", $this->transaction_type);
                $stmt->bindParam(":affected_wallet", $this->affected_wallet);
                $stmt->bindParam(":payment_for", $this->payment_for);
                $stmt->bindParam(":mobile_number", $this->mobile_number);
                $stmt->bindParam(":consumer_number", $this->consumer_number);
                $stmt->bindParam(":operator_code", $this->operator_code);
                $stmt->bindParam(":flight_no", $this->flight_no);
                $stmt->bindParam(":wallet_balance", $this->wallet_balance);
                $stmt->bindParam(":utility_balance", $this->utility_balance);
                $stmt->bindParam(":investment_balance", $this->investment_balance);
                $stmt->bindParam(":earning_balance", $this->earning_balance);
                $stmt->bindParam(":usdt_wallet", $this->usdt_wallet);
            
                if($stmt->execute()){
                return true;
                }
                return false;
            }

            public function createBooking(){
                try {
                      $sqlQuery = "INSERT INTO `tbl_booking_recharge_history` (`id`, `type`, `datetime`, `amount`, `associatenumber`, `status`, `userId`) 
                      VALUES (NULL, :type, CURRENT_TIMESTAMP, :amount, :associatenumber, :status, :user_id);";
                      $stmt = $this->conn->prepare($sqlQuery);
      
                      // sanitize
                      $this->type=htmlspecialchars(strip_tags($this->type));
                      $this->amount=htmlspecialchars(strip_tags($this->amount));
                      $this->associatenumber=htmlspecialchars(strip_tags($this->associatenumber));
                      $this->status=htmlspecialchars(strip_tags($this->status));
                      $this->user_id=htmlspecialchars(strip_tags($this->user_id));
      
                      // bind data
                      $stmt->bindParam(":type", $this->type);
                      $stmt->bindParam(":amount", $this->amount);
                      $stmt->bindParam(":associatenumber", $this->associatenumber);
                      $stmt->bindParam(":status", $this->status);
                      $stmt->bindParam(":user_id", $this->user_id);
      
                      if($stmt->execute()){
                      return true;
                      }
                      
                      return false;
                  }
                  catch(Exception $e) {
                    return false;
                  }
              
          }
          
          public function createScanPayHistory(){
            try {
                  $sqlQuery = "INSERT INTO `tbl_booking_recharge_history` (`id`, `type`, `datetime`, `amount`, `associatenumber`, `status`, `userId`, `sender_no`, `receiver_no`) 
                  VALUES (NULL, :type, CURRENT_TIMESTAMP, :amount, :associatenumber, :status, :user_id, :sender_no ,:receiver_no);";
                  $stmt = $this->conn->prepare($sqlQuery);
  
                  // sanitize
                  $this->type=htmlspecialchars(strip_tags($this->type));
                  $this->amount=htmlspecialchars(strip_tags($this->amount));
                  $this->associatenumber=htmlspecialchars(strip_tags($this->associatenumber));
                  $this->status=htmlspecialchars(strip_tags($this->status));
                  $this->user_id=htmlspecialchars(strip_tags($this->user_id));
                  $this->sender_no=htmlspecialchars(strip_tags($this->sender_no));
                  $this->receiver_no=htmlspecialchars(strip_tags($this->receiver_no));
  
                  // bind data
                  $stmt->bindParam(":type", $this->type);
                  $stmt->bindParam(":amount", $this->amount);
                  $stmt->bindParam(":associatenumber", $this->associatenumber);
                  $stmt->bindParam(":status", $this->status);
                  $stmt->bindParam(":user_id", $this->user_id);
                  $stmt->bindParam(":sender_no", $this->sender_no);
                  $stmt->bindParam(":receiver_no", $this->receiver_no);
  
                  if($stmt->execute()){
                  return true;
                  }
                  
                  return false;
              }
              catch(Exception $e) {
                return false;
              }
          
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

            //vrushali
            public function getWalletBalanceOfUser($userID){
                $sqlQuery = "SELECT wallet_amount FROM customer WHERE id = ?";
                $stmt = $this->conn->prepare($sqlQuery);
                $this->id=htmlspecialchars(strip_tags($userID));
            
            // bind given email value
                $stmt->bindParam(1, $this->id);
                
                $stmt->execute();
                $itemCount = $stmt->rowCount();
                $amout = 0;
                if($itemCount > 0){
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                        $amout = $row['wallet_amount'];
                    }
                }
            
                return $amout;
            }
            // ==========
            

    }