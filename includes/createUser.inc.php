<?php
    session_start();

    if(isset($_POST['create-user']) && isset($_SESSION['userId'])){

        $uid = $_POST['uid'];

        if(empty($uid)){
            header("Location: ../adminpanel.php?error=emptyfields");
            exit();
        } else {
            require 'dbhandler.inc.php';
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $DB = null;
            
            if (!preg_match("/^[a-zA-Z0-9]*$/", $uid)){
                header("Location: ../adminpanel.php?error=invalidusername=".$uid);
                exit();
            } else {
                $sql_uid = "SELECT uid FROM accounts WHERE uid=?";
                $stmt_uid = $conn->stmt_init();

                if(!$stmt_uid->prepare($sql_uid)){
                    header("Location: ../adminpanel.php?error=PREPAREUIDsqlerror");
                    exit();
                } else {
                    $stmt_uid->bind_param("s", $uid);
                    $stmt_uid->execute();
                    $stmt_uid->store_result();
                    $resultCheckUid = $stmt_uid->num_rows();
                    
                    if ($resultCheckUid > 0){
                        header("Location: ../adminpanel.php?error=usernametaken=".$uid);
                        exit();
                    } else {
                        $sql = "INSERT INTO accounts (uid) VALUES (?)";
                        $stmt = $conn->stmt_init();
                        
                        if(!$stmt->prepare($sql)){
                            header("Location: ../adminpanel.php?error=sqlerror");
                            $conn->close();
                            exit(); 
                        } else {
                            $stmt->bind_param("s", $uid);
                            $stmt->execute();
                            header("Location: ../adminpanel.php?info=userCreated");
                        }
                    }  
                }
            }
        }
    } else {
        echo 'Nope nope nope';
    }
                        
?>