<?php
//A class to craete database modeled classes
//Joseph Malaba
// 1st October 2014

include_once('./DynamicConection.php');

class ClassCreator {
    //properties
    private $connection;
    private $foldername;
    private $classes;
    private $properties;
    private $methods;
    
    //constructor
    function _construct(){
        $this->connection = new DynamicConnection();
    }
    
    //other methods
        //conditioning functions
        
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
    
    //table content analyzing functions
    
    
    //file creating functions
    
    //file writing functions
    
    //file reading functions
    
    //folder (diroctory creating functions)
    
    //function to add setters
    
    //function to add getters
    
    //funtion to add properties
    
    //function to add load($id) method, for fetchting and setting properties from the database
    
    //function to add create($p1,$p2....) method which will be used for insert
        
    //setters (for update)
    
    //getters (for select)
}
?>
