        <?php
            require "meta.php";
  
            require "header.php";

            if(isset($_SESSION['userId'])){
                if($Utilities->checkPasswordExpiration($_SESSION['userId']) == 1){
                    require "changepw.php";
                } else {
                    require "main.php";
                }
            } 
            else {
                require "logout.php";
            }
        ?>
    </body>
</html>

