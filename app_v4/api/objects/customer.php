<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "customer";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
    public function getReferList(){
        $sqlQuery = "select * from customer where id in (SELECT child_id FROM `referral` WHERE parent_id = ?)";
        $stmt = $this->conn->prepare($sqlQuery);

        $this->parent_id=htmlspecialchars(strip_tags($this->parent_id));

        $stmt->bindParam(1, $this->parent_id);

        $stmt->execute();
        return $stmt;
    }
    
public function getData(){
    $sqlQuery = "SELECT * FROM " . $this->table_name . " where status = 1";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
}
public function deactivated(){
    $sqlQuery = "SELECT * FROM " . $this->table_name . " where status = 0";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
}
public function getInfo(){
    $sqlQuery = "SELECT * FROM ". $this->table_name ." WHERE id = ? LIMIT 0,1";
    $stmt = $this->conn->prepare($sqlQuery);
    $this->id=htmlspecialchars(strip_tags($this->id));

	// bind given email value
	$stmt->bindParam(1, $this->id);
    
    $stmt->execute();
    return $stmt;
}

public function activedeactive(){
            $sqlQuery = "UPDATE ". $this->table_name ." SET
                                                        status= :status WHERE id = :id";
        
            $stmt = $this->conn->prepare($sqlQuery);
        
            // sanitize
            $this->status=htmlspecialchars(strip_tags($this->status));
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            // bind data
            $stmt->bindParam(":status", $this->status);
            $stmt->bindParam(":id", $this->id);
        
            if($stmt->execute()){
               return true;
            }
            return false;
        }

function Delete(){
            $sqlQuery = "DELETE FROM " . $this->table_name . " WHERE id = ?";
            $stmt = $this->conn->prepare($sqlQuery);
        
            $this->id=htmlspecialchars(strip_tags($this->id));
        
            $stmt->bindParam(1, $this->id);
        
            if($stmt->execute()){
                return true;
            }
            return false;
        }


}