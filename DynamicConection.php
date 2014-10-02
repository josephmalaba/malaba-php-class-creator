<?php
//A class to connect to database dynamically
//Joseph Malaba
// 1st October 2014

class DynamicConnection {
    //properties
    private $dbName;
    private $userName;
    private $password;
    private $connection;
    
    //constructor
    function _construct(){
    }
    
    //methods
    
    //setters
    public function setDbName($DbName){
        $this->connection = $DbName;
    }
    
    //getters
    public function getDbName(){
        return $this->connection;
    }
}

?>
