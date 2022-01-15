<?php
#  This script is assigned to the Login/Password HTML form.  It takes the posted information and performs error checking and
#  database comparison before establishing a session.  If a login fails on an unknown UID, then the script exits and displays
#  an error.  If a login fails on a known UID, it annotates the failed login date/time and iterates a counter in the DB.  If
#  the login succeeds, a session is established, session data is populated, and the database is annotated to show a successful
#  login date/time on the UID.
#
#  This script is dependent on an assocated Database Handler class.  This class is object oriented and contains various methods
#  to assist in database management.
#
#  TODO:  1. Check failed login counter and limit login attempts based on last attempt
#         2. Set lock account after 3 failed attempts
#         3. Add logging triggers
#         4. Move statements to DB class using arguments
#
#  This script designed and maintained by Thundard!
#---------------------------------------------------------Start Script--------------------------------------------------------#

    #Catch the Form POST
    if(isset($_POST['login-submit'])){
      
        #Set the local variables from the Form
        $uid = $_POST['uid'];
        $password = $_POST['pwd'];

        #Check if either Login or Password are empty - exit on true - else on false
        if(empty($uid) || empty($password)){
            header("Location: ../index.php?error=emptyfields");
            exit();
        } else {
            #Setup the Database connection - tear down DB class once connection is established
            require 'dbhandler.inc.php';
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $DB = null;

            #Set up prepared statement that selects the account from Form Login
            $sql = "SELECT * FROM accounts WHERE uid=?;";
            $stmt = $conn->stmt_init();
            
            #Test prepared statement for retrieving UID from DB - exit on failure - else on pass
            if(!$stmt->prepare($sql)){
                header("Location: ../index.php?error=sqlerror");
                $conn->close();
                exit(); 
            } else {
                #Bind the login information from Form to prepared statement, execute statement, store result
                $stmt->bind_param("s", $uid);
                $stmt->execute();
                $result = $stmt->get_result();
                
                #Test if there is NOT a row in the result (No corresponding UID exists)
                if(!$row = $result->fetch_assoc()){
                    #Specified user does not exist in the DB, so close the connection and exit
                    header("Location: ../index.php?error=nouser:".$uid);
                    $conn->close();
                    exit(); 
                } else {
                    #Test the Form password using hash/salt against the hash/salt in DB
                    $pwdCheck = password_verify($password, $row['pwd']);
                    
                    #If password hash/salt is NOT a match - else on True
                    if($pwdCheck == false){
                        header("Location: ../index.php?error=wrongpassword");

                        #Set up prepared statement to mark the account with a failed login date/time and add 1 to the failed login counter
                        $sql_setFailedLogin = "UPDATE accounts SET failedlogin=(?), failedlogin_counter=failedlogin_counter+1 WHERE uid=(?)";
                        $stmt_setFailedLogin = $conn->stmt_init();

                        #Test if prepared statement works - if it fails, then just move on and exit
                        if($stmt_setFailedLogin->prepare($sql_setFailedLogin)){
                            #Execute prepared statement to annotate failed login
                            $stmt_setFailedLogin->bind_param("ss", date('Y-m-d H:i:s'),  $uid);
                            $stmt_setFailedLogin->execute();
                        }

                        $conn->close();
                        exit(); 
                    }
                    #If password hash/salt IS a match
                    else if ($pwdCheck == true){
#---------------------------------------------------------You've Reached a Valid Login/Password Combination--------------------------------------------------------#
                        #Since the Login and Password matches a known user, setup the SESSION and populate session data
                        session_start();
                        $_SESSION['Id'] = $row['id'];
                        $_SESSION['userId'] = $row['uid'];
                        $_SESSION['starttime'] = date('Y-m-d H:i:s');
                        $_SESSION['userLogin'] = "LoggedIn";
                        
                        #Setup prepared statement to update account last login date
                        $sql_setLogin = "UPDATE accounts SET lastlogin=(?) WHERE id=(?)";
                        $stmt_setLogin = $conn->stmt_init();

                        #Test if prepared statement is invalid - else on pass
                        if(!$stmt_setLogin->prepare($sql_setLogin)){
                            #Something must have gone wrong and the login cannot be annotated, so tear down the session so it can be fixed
                            header("Location: ../index.php?loginerr");
                            session_destroy();
                            $conn->close();
                            exit(); 
                        } else {
                            #Bind the Session parameters to the prepared statement and execute
                            $stmt_setLogin->bind_param("ss", $_SESSION['starttime'],  $_SESSION['Id']);
                            $stmt_setLogin->execute();
                            header("Location: ../index.php?login=success:".$_SESSION['starttime']);
                        }

                        $conn->close();
                        exit(); 
                    }
                    #Catch any other errors on password verification and exit
                    else {
                        header("Location: ../index.php?error=somethingisborked");
                        $conn->close();
                        exit(); 
                    }
                }
            }
        } 
    } 
    else {
        #log some shit
    }
?>
