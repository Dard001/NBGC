<?php
    require "meta.php";
    require "header.php";
?>

<main>
    <section> 
        <?php
            if(isset($_SESSION['userId'])){
                $conn = $Utilities->getDBConnection();
                $sql_photos = "SELECT name FROM photos";
                $stmt_photos = $conn->stmt_init();
                $conn1 = $Utilities->getDBConnection();
                $sql_photos1 = "SELECT name, description FROM photos";
                $stmt_photos1 = $conn1->stmt_init();
                $conn2 = $Utilities->getDBConnection();

                    
                if(!$stmt_photos->prepare($sql_photos)){
                        header("Location: ../photoalbum.php?error=sqlerror");
                    exit();
                } elseif(!$stmt_photos1->prepare($sql_photos1)){
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();              
                } else {
                    $stmt_photos->execute();
                    $stmt_photos->store_result();
                    $stmt_photos1->execute();
                    $result = $stmt_photos1->get_result();
                    $resultCheckPhotos = $stmt_photos->num_rows();
                }  
                ?>
                <div class="album-layout">
                    <div class="album-area"> 
                        <?php 
                        while($photo = $result->fetch_array()){
                            echo    '<div class="album-photo">
                                        <a target="_blank" href="./photos/'.$photo['name'].'.jpg">
                                            <img title="'.$photo['description'].'" src=./photos/'.$photo['name'].'.jpg>
                                        </a>
                                    </div>';
                        }
                        ?>
                    </div>
                </div>
                                
                <div class="album-layout">
                        <form action="./includes/uploadImage.inc.php" method="post" enctype="multipart/form-data"> 
                            <input type="text" name="image-description" placeholder="Image Description">
                            <input type="file" accept=".jpg" name="image-file" id="image-file">
                            <input type="submit" name="upload-image" value="Upload Image">
                        </form> 
                </div>
                    <?php
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

