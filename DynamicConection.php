<?php
//A class to connect to database dynamically
//Joseph Malaba
// 1st October 2014

class DynamicConnection {
    //properties
    private $dsn ;
    private $user;
    private $password;
    private $pdo;
    private $result;
    
    public function connect($host,$user,$pwd){
         $this->dsn = 'mysql:dbname=waubani_db;host='.$host;
         $this->user = $user;
         $this->password = $pwd;
    
    
        try{
            $this->pdo = new PDO($dsn,$user,$password);
        }catch(PDOException $e){
           echo  "Connection Failed : ". $e->getMessage();
        }
    }
    
    public function execute_query($qr){
        $this->result = $this->pdo->query($qr);
    }
    
    public function get_result(){
        return $this->result;
    }
}

?>
