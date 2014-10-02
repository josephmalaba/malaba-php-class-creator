<?php
//A class to craete database modeled classes
//Joseph Malaba
// 1st October 2014

include_once('./DynamicConection.php');

class ClassCreator {
    //properties
    private $foldername;
    private $classes;
    private $properties;
    private $methods;
    private $con;
    
    //constructor
    function _construct(){
        //$this->con = new DynamicConnection();
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
    
    //conmection function
    public function startConnection($host,$user,$pwd,$db){
        $this->con = new DynamicConnection($host,$user,$pwd,$db);
    }
    
    //table content analyzing functions
    
    
    //file creating functions
    public function createFile($fileName,$content){
        $phpfile = fopen($fileName.".php", "w") ;
        fwrite($phpfile, $content);
        fclose($phpfile);
    }
    //file writing functions (the above funtion also does the same thing)
    
    //file reading functions
    
    //folder (diroctory creating functions)
    public function createDirectory($directory){
        if(mkdir($directory)){
            return true;
        }else{
            return false;
        }
    }
    
    //function to add setters
    public function addSetters($tableName){
        $sql = "DESCRIBE $tableName";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();

        $setter = '';
        foreach($tableresult as $row){
            $setter.= "public function set".ucfirst(strtolower($row['Field']))."($".strtolower($row['Field'])."){\n";
            $setter.= "\t$"."updateQr = \"UPDATE $tableName SET ".$row['Field']."='$".strtolower($row['Field'])."'\";\n\n";
            
            $setter.= "\t$"."this->con->execute_query($"."updateQr);\n\n";
            
            $setter.= "\t$"."this->".strtolower($row['Field'])."=$".strtolower($row['Field']).";\n";
            $setter.= "}\n\n";
        }
        return $setter;
    }
    
    //function to add getters
    
    //funtion to add properties
    
    //function to add load($id) method, for fetchting and setting properties from the database
    
    //function to add create($p1,$p2....) method which will be used for insert
        
    //setters (for update)
    
    //getters (for select)
}
?>
