<?php
class dbHandler{
    private $servername = "localhost";
    private $serverport = "3306";
    private $dBUsername = "nbgc";
    private $dBPassword = "";
    private $dBName = "nbgc";
    
    #Establish the connection to DB
    #Test of connection was successful
    #Return the successful connection
    private function setupDBConnection(){
        $conn = mysqli_connect($this->servername, $this->dBUsername, $this->dBPassword, $this->dBName);
    
        if (!$conn){
            die("Connection failed: ".mysqli_connect_error());      
        } else {
            return $conn;
        }
    }
    
    #Take whatever connection the source is using
    #Setup the SQL INSERT statement
    #Test the statement
    #Bind the source parameters to statement
    #Execute the statement
    public function commitLog($conn, $type, $uid, $IP, $content){
        $stmt = $conn->stmt_init();
        $query = "INSERT INTO logs (type, uid, IP, content) VALUES (?, ?, ?, ?)";

        if(!$stmt->prepare($query)){
            header("Location: ../index.php?error=PREPAREsqlerror");
        } else {
            $stmt->bind_param("ssss", $type, $uid, $IP, $content);
            $stmt->execute();
        }     
    } 
    
    public function changePW($conn, $uid, $hashedPwd){
        $pwd_change=0;
        $stmt = $conn->stmt_init();
        $query = "UPDATE accounts SET pwd=?, pwd_change=? WHERE uid=?";

        if(!$stmt->prepare($query)){
            header("Location: ../index.php?error=PREPAREsqlerror");
        } else {
            $stmt->bind_param('sis', $hashedPwd, $pwd_change, $uid);
            $stmt->execute();
        }  
    }
    
    #Public interface to setup a connection
    public function getDBConnection(){
        return $this->setupDBConnection();
    } 
}
?>
