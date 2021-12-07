<?php
    if(isset($_POST['login-submit'])){
      
        $uid = $_POST['uid'];
        $password = $_POST['pwd'];

        if(empty($uid) || empty($password)){
            header("Location: ../index.php?error=emptyfields");
            exit();
        }
        else {
            require 'dbhandler.inc.php';
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $DB = null;
            $sql = "SELECT * FROM accounts WHERE uid=?;";
            $stmt = $conn->stmt_init();
            
            if(!$stmt->prepare($sql)){
                header("Location: ../index.php?error=sqlerror");
                $conn->close();
                exit(); 
            }
            else {
                $stmt->bind_param("s", $uid);
                $stmt->execute();
                $result = $stmt->get_result();
                
                if($row = $result->fetch_assoc()){                 
                    $pwdCheck = password_verify($password, $row['pwd']);
                    
                    if($pwdCheck == false){
                        header("Location: ../index.php?error=wrongpassword_1");
                        $conn->close();
                        exit(); 
                    }
                    else if ($pwdCheck == true){
                        session_start();
                        $_SESSION['Id'] = $row['id'];
                        $_SESSION['userId'] = $row['uid'];
                        $_SESSION['starttime'] = time();
                        $_SESSION['userLogin'] = "LoggedIn";
                        
                        header("Location: ../index.php?login=success");
                        #$Utilities->setSession($_SESSION, $_SERVER);
                        #$IP = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR'];
                        #$Utilities->commitLog($conn, "login_success", $_SESSION['userId'],$IP, $_SESSION['userId']." successfully logged in at ".date('l jS \of F Y h:i:s A'));
                        $conn->close();
                        exit(); 
                    }
                    else {
                        header("Location: ../index.php?error=wrongpassword_2");
                        $conn->close();
                        exit(); 
                    }
                }
                else {
                    header("Location: ../index.php?error=nouser__".$uid);
                    $conn->close();
                    exit(); 
                }
            }
        } 
    } 
    else {
        header("Location: ../index.php?error=clevergirl");
        $conn->close();
        exit();
    }
?>
