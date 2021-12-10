<?php
    if(isset($_SESSION['userId'])){
?>
        <main>
                <section>
                    <div class="page-title">
                    You need to change your password.
                    </div>
                    <div class="widget">
                        <form action="./includes/changePW.inc.php" method="post">
                            <p><input type="password" name="password_old" placeholder="Old Password...">
                            <p><input type="password" name="password_new" placeholder="New Password...">
                            <p><input type="password" name="password_retype" placeholder="Retype Password...">
                            <p><input type="submit" name="password-submit" value="Change Password">
                        </form>
                    </div>
                </section>
            </main>;
<?php
    } else {
        require "logout.php";
    }
?>