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
            $statemente = "if( ".$condition." ){\n\t\t".$outcome.";\n\t}";
            return $statemente;
        }
        
        public function addElse($outcome){
            $statemente = "else{ ".$outcome.";}";
            return $statemente;
        }
        
        public function addElseIf($condition,$outcome){
            $statemente = "elseif( ".$condition." ){\n\t\t".$outcome."; \n\t}";
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
            
            $condition = " $"."this->con->execute_query($"."updateQr) ";
            
            $outcome = "\t$"."this->".strtolower($row['Field'])."=$".strtolower($row['Field']).";\n";
            $setter.= $this->addIf($condition,$outcome);
            
            $setter.= "}\n\n";
        }
        return $setter;
    }
    
    //function to add getters
    public function addGetters($tableName){
        $getter = '';
        foreach($tableresult as $row){
            $getter.= "public function get".ucfirst(strtolower($row['Field']))."(){\n";
            $getter.= "\t return $"."this->".strtolower($row['Field']).";\n";
            $getter.= "}\n\n";
        }
        return $getter;
    }
    
    //funtion to add properties
    public function addProperty($tableName){
        $sql = "DESCRIBE $tableName";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();

        $property = '';
        foreach($tableresult as $row){
            $property.= "\t\\\\Properties\n";
            $property.= "\tprivate $".lcfirst($row['Field']).";\n";
            $property.= "\tprivate $"."con".";\\\\Connection Object \n";
            $property.= "\n\n";
        }
        return $property;
    }
    
    //function to add load($id) method, for fetchting and setting properties from the database
    public function addLoader($tableName){
        $sql = "DESCRIBE $tableName";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();
        
        //Checking for Primary Keys
        $key = '';
        $keyCondtion='';
        $keyVal = 0;
        $properties='';
        foreach($tableresult as $row){
            if($row['Key']=='PRI'){
                if($keyVal==0){
                    $key.=" $".$row['Field']." ";
                    $keyCondtion.=" WHERE ".$row['Field']."='$".strtolower($row['Field'])."'\";\n\n";
                }else{
                    $keyCondtion.=" AND ".$row['Field']."='$".strtolower($row['Field'])."'\";\n\n";
                    $key.=",$".$row['Field']." ";
                }
            }
            $properties.= "\t\t\t$"."this->".lcfirst($row['Field'])."= $"."row[\"".$row['Field']."\"];\n";
            $keyVal++;
        }
        
        $loader = '';
        foreach($tableresult as $row){
            $loader.= "public function load($key){\n"; //first add keys(primary keys if any) in parameter list
            $loader.= "\t$"."selectQr = \"SELECT * FROM $tableName $keyCondtion";
            
            $condition = " $"."this->con->execute_query($"."updateQr) ";
            
            $outcome = "\t$"."this->".strtolower($row['Field'])."=$".strtolower($row['Field']).";\n";
            $setter.= $this->addIf($condition,$outcome);
            
            $setter.= "}\n\n";
        }
        return $loader;
    }
    
    
    //function to add create($p1,$p2....) method which will be used for insert
        
    //setters (for update)
    
    //getters (for select)
    
}
?>
