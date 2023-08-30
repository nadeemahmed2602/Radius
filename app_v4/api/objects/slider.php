<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "slider";
 
    // object properties
    public $id;
    public $name;
    public $email;
    public $password;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }
 
public function getData(){
    $sqlQuery = "SELECT * FROM " . $this->table_name . " order by id desc";
    $stmt = $this->conn->prepare($sqlQuery);
    $stmt->execute();
    return $stmt;
}


}