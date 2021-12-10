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
                $sql_photos1 = "SELECT name, description, tag FROM photos";
                $stmt_photos1 = $conn1->stmt_init();
                $conn2 = $Utilities->getDBConnection();
                $sql_tags = "SELECT tag FROM ref_tags";
                $stmt_tags = $conn1->stmt_init();
                $conn3 = $Utilities->getDBConnection();

                    
                if(!$stmt_photos->prepare($sql_photos)){
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();
                } elseif(!$stmt_photos1->prepare($sql_photos1)){
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();              
                } elseif(!$stmt_tags->prepare($sql_tags)) {
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();
                } else {
                    $stmt_photos->execute();
                    $stmt_photos->store_result();
                    $stmt_photos1->execute();
                    $result = $stmt_photos1->get_result();
                    $resultCheckPhotos = $stmt_photos->num_rows();
                    $stmt_tags->execute();
                    $tags= $stmt_tags->get_result();
                }  
                ?>

                <div class="album-layout">
                            <form action="#" method="post">
                                <input list="set_tag" name="uid">
                                <datalist id="set_tag">';
                                <?php
                                    while($tag = $tags->fetch_array()){
                                        echo '<option value="'.$tag['tag'].'" />';
                                    }

                                ?>
                                </datalist>
                                <input type="submit" name="set-tag" value="Filter Tags">
                            </form> 
                </div>

                <div class="album-layout">
                    <div class="album-area"> 
                        <?php 
                        while($photo = $result->fetch_array()){

                            if($photo['tag'] == "#ALL"){
                                echo    '<div class="album-photo">
                                            <a target="_blank" href="./photos/'.$photo['name'].'">
                                                <img title="'.$photo['description'].'" src=./photos/'.$photo['name'].'>
                                            </a>
                                        </div>';
                            }
                        }
                        ?>
                    </div>
                </div>
                                
                <div class="album-layout">
                        <form action="./includes/uploadImage.inc.php" method="post" enctype="multipart/form-data"> 
                            <input type="text" name="image-description" placeholder="Image Description">
                            <input type="file" accept=".jpg,.jpeg,.png,.gif" name="image-file" id="image-file">
                            <input type="submit" name="upload-image" value="Upload Image">
                        </form> 
                        * only gif, jpeg, jpg, and png images of less than 5MB are allowed
                </div>
                    <?php
            } else {
                require "logout.php";
            }
            ?>
        </section>
    </main>
        <?php
         require"footer.php";
         ?>
    </body>
</html>

