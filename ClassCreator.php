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
        if(@mkdir($directory)){
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
            
            $condition = "$"."this->con->execute_query($"."updateQr)";
            
            $outcome = "\t$"."this->".strtolower($row['Field'])."=$".strtolower($row['Field'])."";
            $setter.= $this->addIf($condition,$outcome);
            
            $setter.= "}\n\n";
        }
        return $setter;
    }
    
    //function to add getters
    public function addGetters($tableName){
        $sql = "DESCRIBE $tableName";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();
        
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

        $property = "//Properties\n";
        $property.= "\tprivate $"."con".";//Connection Object \n";
        foreach($tableresult as $row){
            $property.= "\tprivate $".lcfirst($row['Field']).";\n";
        }
        return $property."\n\n";
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
            $loader.= "\t$"."selectQr = \"SELECT * FROM $tableName $keyCondtion ;";
            
            $condition = " $"."this->con->execute_query($"."selectQr) ";
            
            $outcome = $properties;
            $loader.= $this->addIf($condition,$outcome);
            
            $setter.= "}\n\n";
        }
        return $loader;
    }
    
    
    //function to add create($p1,$p2....) method which will be used for insert
    public function addCreator($tableName){
        $sql = "DESCRIBE $tableName";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();
        
        //Checking for Primary Keys
        $fieldVal = '';
        $propertyList='';
        $propertyListValue='';
	$keyVal = 0;
        foreach($tableresult as $row){
            if($row['Extra']!='auto_increment'){
                if($keyVal==0){
                 $fieldVal.=" $".$row['Field']." ";
                 $propertyList.=" '".$row['Field']."' ";
                 $propertyListValue.=" '$".$row['Field']."' ";
                }else{
                    $fieldVal.=",$".$row['Field']." ";
                    $propertyList.=",'".$row['Field']."' ";
                    $propertyListValue.=",'$".$row['Field']."' ";
                }
            }
            $keyVal++;
        }
        
        $creator = '';
        foreach($tableresult as $row){
            $creator.= "public function create".ucfirst($tableName)."($fieldVal){\n"; //first add keys(primary keys if any) in parameter list
            $selectQr = "\t$"."insertQr = \"INSERT INTO $tableName($propertyList) VALUES($propertyListValue);";
			
            $condition = " $"."this->con->execute_query($"."insertQr) ";
            $outcome = $properties;
            $creator.= $this->addIf($condition,$outcome);
            
            $creator.= "}\n\n";
        }
        return $creator;
    }
    //a function to call all other functions and create all classes from db...
    public function createClases(){
        $sql = "SHOW TABLES";
        $this->con->execute_query($sql);
        $tableresult = $this->con->get_result();
        foreach($tableresult as $row){
            $properties = $this->addProperty($row[0]);
            $loader = $this->addLoader($row[0]);
            $setters = $this->addSetters($row[0]);
            $getters = $this->addGetters($row[0]);
            $creator = $this->addCreator($row[0]);
            
            $filecontent = "<?php\n// a class reflecting table ".$row[0]."\n";
            $filecontent.="class ".ucfirst(str_replace('_','',str_replace('tbl','',strtolower($row[0]))));
            $filecontent.="{\n".$properties.$loader.$setters.$getters.$creator."}\n?>";
            
            $directory = "./ClassCreator";
            $this->createDirectory($directory);
            $fileName = $directory."/".ucfirst(str_replace('_','',str_replace('tbl','',strtolower($row[0])))).".php";
            $this->createFile($fileName,$filecontent);
        }
    }
}

/* $create = new ClassCreator();
$create->startConnection('localhost','root','','db_name');
$create->createClases(); */
?>