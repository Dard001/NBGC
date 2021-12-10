<?php
    require "dbhandler.inc.php";
    
    class sessionUtilities{
        private $DBHandler;
        private $SESSION;
        private $SERVER;
               
        public function __construct(){
            $this->DBHandler = new dbHandler();
        }
        
        public function getDBConnection(){
            return $this->DBHandler->getDBConnection();
        } 
        
        public function commitLog($conn, $type, $uid, $IP, $content){
            $this->DBHandler->commitLog($conn, $type, $uid, $IP, $content);
        }
        
        public function tearDown($conn){
            $conn->close();
            session_unset();
            session_destroy();
        }
        
        public function changePW($uid, $pwd){
            $IP = isset($SERVER['HTTP_CLIENT_IP']) ? $SERVER['HTTP_CLIENT_IP'] : isset($SERVER['HTTP_X_FORWARDED_FOR']) ? $SERVER['HTTP_X_FORWARDED_FOR'] : $SERVER['REMOTE_ADDR'];
            $conn = $this->getDBConnection();

            $stmt = $conn->stmt_init();
            $query = "UPDATE accounts SET pwd=?, pwd_change=? WHERE uid=?";

            if(!$stmt->prepare($query)){
                header("Location: ../index.php?error=PREPAREsqlerror");
            } else {
                $stmt->bind_param('sis', $hashedPwd, 0, $uid);
                $stmt->execute();
            }  

            $this->commitLog($conn, 'password_change', $uid, $IP, "Password changed for user: ".$uid);
        }
        
        public function checkAdmin($uid){
            $conn = $this->getDBConnection();
            $stmt = $conn->stmt_init();
            $query = "SELECT isadmin FROM accounts WHERE uid='".$uid."'";

            if(!$stmt->prepare($query)){
                #Handle an error
            } else {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_array()){
                    return $isadmin = $row['isadmin'];
                }
            }
        }
        
        public function checkPasswordExpiration($uid){
            $conn = $this->getDBConnection();
            $stmt = $conn->stmt_init();
            $query = "SELECT pwd_change FROM accounts WHERE uid='".$uid."'";

            if(!$stmt->prepare($query)){
                #Handle error
            } else {
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_array()){
                    return $checkChangePW = $row['pwd_change'];
                }
            }
        }
    }
?>