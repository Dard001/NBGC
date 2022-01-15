<?php
#  This script is assigned to an HTML Form which specifies a User ID is in need of a password change.  It is meant to be used by an
#  administrator to force a user to change their password.  It does not immediately prompt for a password change however.  What it does
#  is change a boolean cell value for the corresponding UID from a 0 to a 1.  When the user logs in, the website will check this value.
#  When the DB returns a 1, it forces the user to either logout or change their password.
#
#  TODO: 1. Add logging to the script
#        2. Add a dependency on other pages to check for the password reset boolean
#        3. Change how the 'if ($resultCheckUid == 0){' works.  It's a bit wonky and should look more like ' if(!$row = $result->fetch_assoc()){'
#
#  This script designed and maintained by Thundard!
#---------------------------------------------------------Start Script--------------------------------------------------------#
    #Load existing Session data (if session isn't valid, it won't have the appropriate metadata)
    session_start();

    #Check if the session belongs to a set UID
    if (isset($_SESSION['userId'])){
         #Catch the Form POST
         if(isset($_POST['set-change-password'])){

            #Set up the local variables from the Form
            $uid = $_POST['uid'];

            #Check if the Form provided UID is blank - exit on true - else on false
            if(empty($uid)){
                header("Location: ../adminpanel.php?error=emptyfields");
                exit();
            } else {
                #If a UID is provided, it must be checked, so setup the database connection, then teardown the DB class
                require 'dbhandler.inc.php';
                $DB = new dbHandler();
                $conn = $DB->getDBConnection();
                $DB = null;
                
                #Setup the propared statement to check for valid UID
                $sql_uid = "SELECT uid FROM accounts WHERE uid=?";
                $stmt_uid = $conn->stmt_init();

                #Test prepared statement - exit on failure - else on pass
                if(!$stmt_uid->prepare($sql_uid)){
                    header("Location: ../adminpanel.php?error=PREPAREUIDsqlerror");
                    exit();
                } else {
                    #Bind the Form variables to prepared statement, execute, and store result
                    $stmt_uid->bind_param("s", $uid);
                    $stmt_uid->execute();
                    $stmt_uid->store_result();

                    #Calulate the number of rows in the result
                    $resultCheckUid = $stmt_uid->num_rows();

                    #Check if there are no matching UIDs
                    if ($resultCheckUid == 0){
                        header("Location: ../adminpanel.php?error=nouser=".$uid);
                        exit();
                    } else {
#---------------------------------------------------------All prechecks complete, start executing--------------------------------------------------------#
                        #If there is a matching UID, create prepared statement to mark password change requirement in DB
                        $sql = "UPDATE accounts SET pwd_change=? WHERE uid=?";
                        $stmt = $conn->stmt_init();

                        #Test prepared statement - exit on fail - else on pass
                        if(!$stmt->prepare($sql)){
                            header("Location: ../adminpanel.php?error=sqlerror");
                            $conn->close();
                            exit(); 
                        } else {
                            #Execute the statement to mark UID for password change requirement in DB
                            $boolean = 1;
                            $stmt->bind_param("ss", $boolean, $uid);
                            $stmt->execute();
                            header("Location: ../adminpanel.php?info=setChangePW");
                        }
                    }  
                }
            }
        }
    } else {
         #log some shit
    }