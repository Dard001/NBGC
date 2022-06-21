<?php
    require "meta.php";
    require "header.php";
?>

        <main>
            <section> 
                <?php
                    if(isset($_SESSION['userId'])){
                ?>

                    <div class="container"> 
                        <?php
                            require "calendar-modal.php";
                            require "calendar-app.php";
                        ?>
                    </div>

                    <script src="scripts/calendar-script.js" type="text/javascript"></script>

                    <?php 
                        } else {
                            require "logout.php";
                        }
                    ?>
            </section>
        </main>
    </body>
</html>