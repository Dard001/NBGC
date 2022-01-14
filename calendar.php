<?php
    require "meta.php";
    require "header.php";
?>

        <main>
            <section> 
                <?php
                if(isset($_SESSION['userId'])){
                ?>
                    <div class="main-layout"> 
                        <div class="calendar-layout">

                            <?php   
                                include './includes/calendar.inc.php';

                                $calendar = new NBGCCalendar();

                                echo $calendar->show();
                            ?>
                        </div>
                    </div>
                    <?php 
                        } else {
                            require "logout.php";
                        }
                    ?>
            </section>
        </main>
    </body>
</html>