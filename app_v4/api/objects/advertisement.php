<?php
// 'user' object
class User{
 
    // database connection and table name
    private $conn;
    private $table_name = "advertisement";
 
    // object properties
    public $id;
    public $image;
 
    // constructor
    public function __construct($db){
        $this->conn = $db;
    }

    // Get Advertisement
    public function getAdvertisement(){
        $sqlQuery = "SELECT * FROM " . $this->table_name . " where id = 1";
        $stmt = $this->conn->prepare($sqlQuery);
        $stmt->execute();
        return $stmt;
    }

}