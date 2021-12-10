
    <body>
        <script src="js/scripts.js"></script>
        <header>
            <div class="site-title">
                NBGC Clubhouse
            </div>

            <div class="flex-navbar">            
            <?php
                if(isset($_SESSION['userId']) && $Utilities->checkPasswordExpiration($_SESSION['userId']) == 0){
                echo    '<div class="dropdown">
                        <div class="dropbtn">
                            <a href="index.php">HOME</a>
                        </div>
                    </div><!--End Dropdown-->
                    <div class="dropdown">
                        <div class="dropbtn">
                            <a href="#">MESSAGES</a>
                        </div>
                    </div><!--End Dropdown-->
                    <div class="dropdown">
                        <div class="dropbtn">THINGS</div>
                            <div class="dropdown-content">
                                <a href="#">Calendar</a>
                                <a href="#">Reminders</a>
                                <a href="photoalbum.php">Photo Album</a>
                                <a href="#">Links</a>
                            </div>
                    </div><!--End Dropdown-->
                    <div class="dropdown">
                        <div class="dropbtn">ACCOUNT</div>
                            <div class="dropdown-content">
                                <a href="#">View Profile</a>
                                <a href="#">Account Settings</a>';
                                                                
                                if ($Utilities->checkAdmin($_SESSION['userId']) == 1){
                                    echo '<a href="adminpanel.php">Admin Controls</a>';
                                }
                                    
                    echo    '</div>
                     </div><!--End Dropdown-->';
                    }
                    ?>
                </div><!--End Navbar-->

                <div class="flex-login">
                    <?php

                    if(isset($_SESSION['userId'])){
                        echo '<form action="./includes/logout.inc.php" method="post">
                                    <input type="submit" name="logout-submit" value="LOGOUT">
                              </form>';
                    } 
                    else {
                        echo '  <form action="./includes/login.inc.php" method="post">
                                    <input type="text" name="uid" placeholder="Username...">
                                    <input type="password" name="pwd" placeholder="Password...">
                                    <input type="submit" name="login-submit" value="LOGIN">
                                </form>';
                    }

                ?>
            </div><!--End Right Side Header-->
        </header>
