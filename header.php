
    <body>
        <script src="js/scripts.js"></script>
        <header>
            <div class="site-title">
                NBGC Clubhouse
            </div>

            <div class="flex-navbar">            
            <?php
                if(isset($_SESSION['userId']) && $Utilities->checkPasswordExpiration($_SESSION['userId']) == 0){
                ?>
               <div class="dropdown">
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
                        <div class="dropbtn">APPS</div>
                            <div class="dropdown-content">
                                <a href="calendar.php">Calendar</a>
                                <a href="#">Reminders</a>
                                <a href="#">Discussions</a>
                                <a href="photoalbum.php">Photo Album</a>
                                <a href="links.php">Links</a>
                            </div>
                    </div><!--End Dropdown-->
                    <div class="dropdown">
                        <div class="dropbtn">ACCOUNT</div>
                            <div class="dropdown-content">
                                <a href="#">View Profile</a>
                                <a href="#">Account Settings</a>
                                              <?php                  
                                if ($Utilities->checkAdmin($_SESSION['userId']) == 1){
                                ?>
                                    <a href="adminpanel.php">Admin Controls</a>
                                    <?php
                                }
                                ?>
                                    
                    </div>
                     </div><!--End Dropdown-->
                     <?php
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
                ?>
            </div><!--End Right Side Header-->
        </header>
