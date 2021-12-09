<?php
     if(isset($_POST['set-change-password'])){

        $uid = $_POST['uid'];

        if(empty($uid)){
            header("Location: ../adminpanel.php?error=emptyfields");
            exit();
        } else {
            require 'dbhandler.inc.php';
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $DB = null;
            
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

                if ($resultCheckUid == 0){
                    header("Location: ../adminpanel.php?error=nouser=".$uid);
                    exit();
                } else {
                    $sql = "UPDATE accounts SET pwd_change=? WHERE uid=?";
                    $stmt = $conn->stmt_init();

                    if(!$stmt->prepare($sql)){
                        header("Location: ../adminpanel.php?error=sqlerror");
                        $conn->close();
                        exit(); 
                    } else {
                        $boolean = 1;
                        $stmt->bind_param("ss", $boolean, $uid);
                        $stmt->execute();
                        header("Location: ../adminpanel.php?info=setChangePW");
                    }
                }  
            }
        }
    }