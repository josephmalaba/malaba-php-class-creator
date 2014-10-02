<?php
//A class to craete database modeled classes
//Joseph Malaba
// 1st October 2014

include_once('./DynamicConection.php');

class ClassCreator {
    //properties
    private $connection;
    
    //constructor
    function _construct(){
        $this->connection = new DynamicConnection();
    }
    
    //other methods
    public function addIf($condition,$outcome){
        $statemente = "if( ".$condition." ){ ".$outcome.";}";
        return $statemente;
    }
    
    public function addElse($outcome){
        $statemente = "else{ ".$outcome.";}";
        return $statemente;
    }
    
    public function addElseIf($condition,$outcome){
        $statemente = "elseif( ".$condition." ){ ".$outcome.";}";
        return $statemente;
    }
    
    //setters
    public function setConnection($con){
        $this->connection = $con;
    }
    
    //getters
    public function getConnection(){
        return $this->connection;
    }
}
?>
