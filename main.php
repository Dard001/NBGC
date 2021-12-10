<main>
    <section>
      
        <?php
            if(isset($_SESSION['userId'])){
        ?>
                        <div class="main-layout"> 
                            <div class="main-column">
                                <div class="main-marquee">
                                <?php
                                    $conn = $Utilities->getDBConnection();
                                    $sql = "SELECT CONCAT(date,' - ',event) from events ORDER BY id DESC LIMIT 10;";
                                    $stmt = mysqli_stmt_init($conn);

                                    if(!mysqli_stmt_prepare($stmt, $sql)){
                                        echo 'ERROR IN EVENTS';
                                        exit(); 
                                    }
                                    else {
                                        mysqli_stmt_execute($stmt);
                                        $result = mysqli_stmt_get_result($stmt);
                                        while($row = $result->fetch_assoc()){
                                            foreach($row as $field => $value){
                                                echo "<p>".$value."</p>";
                                            }
                                        }
                                    }
                                ?>
                                </div>
                            </div>
                        </div>
                        <div class="main-layout">
                            <div class="main-left-column">Column1
                                <div class="main-row">Row-1-1</div>
                                <div class="main-row">Row-1-2</div>
                                <div class="main-row">Row-1-3</div>
                            </div>
                            <div class="main-center-column">Column2
                                <div class="main-row">Row-2-1</div>
                                <div class="main-row">Row-2-2</div>
                                <div class="main-row">Row-2-3</div>
                            </div>
                        </div>
                        

        <?php
            } else {
                require "logout.php";
            }
        ?>  
    </section>
</main>
