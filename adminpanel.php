<?php
    require "meta.php";
    require "header.php";
?>

<main>
    <section> 
        <?php
            if(isset($_SESSION['userId']) && $Utilities->checkAdmin($_SESSION['userId']) == 1){
                $conn = $Utilities->getDBConnection();
                $sql_uid = "SELECT uid FROM accounts";
                $stmt_uid = $conn->stmt_init();

                if(!$stmt_uid->prepare($sql_uid)){
                    header("Location: ../adminpanel.php?error=PREPAREUIDsqlerror");
                    exit();
                }

                $stmt_uid->execute();
                $result= $stmt_uid->get_result();
        ?>

                <div class="admin-layout">
                    <div class="admin-column">Website Functions
                        <div class="admin-row">
                            <p>Create New User</p>                                 
                            <form action="./includes/createUser.inc.php" method="post">
                                <input type="text" name="uid" placeholder="New Username">
                                <input type="submit" name="create-user" value="Create User">
                            </form> 
                        </div>
                        <div class="admin-row">
                            <p>Set Change Password</p>
                            <form action="./includes/setChangePW.inc.php" method="post">
                                <input list="set_chg_pw_users" name="uid">
                                <datalist id="set_chg_pw_users">';
                                <?php
                                    while($user = $result->fetch_array()){
                                        echo '<option value="'.$user['uid'].'" />';
                                    }

                                ?>
                                </datalist>
                                <input type="submit" name="set-change-password" value="Set PW Change">
                            </form> 
                        </div>
                        <div class="admin-row">
                            <p>Reset Password</p>
                            <form action="./includes/resetPassword.inc.php" method="post">
                                <input list="rest-pw-users" name="user">
                                <datalist id="rest-pw-users">
                                <?php
                                $stmt_uid->execute();
                                $result= $stmt_uid->get_result();
                                    while($user = $result->fetch_array()){
                                        echo '<option value="'.$user['uid'].'" />';
                                    }
                                ?>
                                </datalist>
                                <input type="submit" name="reset-pw-submit" value="Reset PW">
                            </form> 
                        </div>
                        <div class="admin-row">
                            <p>Toggle Admin</p>
                            <form action="./includes/toggleAdmin.inc.php" method="post">
                                <input list="users" name="user">
                                <datalist id="users">
                                <?php
                                    $stmt_uid->execute();
                                    $result= $stmt_uid->get_result();
                                    while($user = $result->fetch_array()){
                                        echo '<option value="'.$user['uid'].'" />';
                                    }
                                ?>
                                </datalist>
                                <input type="submit" name="login-submit" value="Toggle Admin">
                            </form> 
                        </div>
                    </div>
                            
                    <div class="admin-column">Database Functions
                        <div class="admin-row">
                            <p>Clear Log</p>
                            <form action="#" method="post">
                                <input type="submit" name="login-submit" value="Clear Log">
                            </form> 
                        </div>
                        <div class="admin-row">
                            <p>Reset Admins</p>
                            <form action="#" method="post">
                                <input type="submit" name="login-submit" value="Reset Admins">
                            </form> 
                        </div>
                        <div class="admin-row">
                            <p>Set Change All Passwords</p>
                            <form action="#" method="post">
                                <input type="submit" name="login-submit" value="Toggle Resets">
                            </form> 
                        </div>
                    </div>
                </div>';
        <?PHP
            } else {
                echo "Clever girl, but you're not supposed to be here";      
            }
        ?>
        </section>
    </main>
    <?php
        require"footer.php";
    ?>
    </body>
</html>

