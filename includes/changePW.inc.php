<?php
    session_start();

    if(isset($_POST['password-submit']) && isset($_SESSION['userId'])){
        
        $uid = $_SESSION['userId'];
        $password_old = $_POST['password_old'];
        $password_new = $_POST['password_new'];
        $password_retype = $_POST['password_retype'];
        
        #Check for empty fields
        if(empty($password_old) || empty($password_new) || empty($password_retype)){
            header("Location: ../index.php?error=emptyfields");
            exit();
        }
        
        #Check if new PW and Retype PW don't match
        if ($password_new != $password_retype){
            header("Location: ../index.php?error=newpasswordmismatch");
            exit();
        }
        
        #Check for PW strength
        $uppercase = preg_match('@[A-Z]@', $password_new);
        $lowercase = preg_match('@[a-z]@', $password_new);
        $number = preg_match('@[0-9]@', $password_new);
        $specialChars = preg_match('@[^\w]@', $password_new);
        
        if(!$uppercase || !$lowercase || !$number || !$specialChars || strlen($password_new) < 8) {
            header("Location: ../index.php?error=weaknewpassword");
            exit();
        } else {
            
            #Proceed with PW change
            require "dbhandler.inc.php";
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $sql = "SELECT * FROM accounts WHERE uid=?;";
            $stmt = $conn->stmt_init();

            if(!$stmt->prepare($sql)){
                header("Location: ../index.php?error=sqlerror");
                $conn->close();
                exit(); 
            } else {
                $stmt->bind_param("s", $uid);
                $stmt->execute();
                $result = $stmt->get_result();   
            } 
            if($row = $result->fetch_assoc()){                 
                $pwdCheck = password_verify($password_old, $row['pwd']);

                if($pwdCheck == false){
                    header("Location: ../index.php?error=wrongpassword_1");
                    $conn->close();
                    exit(); 
                } else if ($pwdCheck == true){
                    $hashedPwd = password_hash($password_new, PASSWORD_DEFAULT);
                    
                    $conn = $DB->getDBConnection();

                    $stmt = $conn->stmt_init();
                    $query = "UPDATE accounts SET pwd=?, pwd_change=? WHERE uid=?";

                    if(!$stmt->prepare($query)){
                        header("Location: ../index.php?error=PREPAREsqlerror");
                    } else {
                        $sss = 'sss';
                        $zero = '0';
                        $stmt->bind_param($sss, $hashedPwd, $zero, $uid);
                        $stmt->execute();
                        header("Location: ../index.php?login=changepwsuccess");
                    }  
                   
                    
                    $conn->close();
                    exit(); 
                }
            }
        }
    } else {
         #log some shit
    }