<?php
    require "meta.php";
    require "header.php";
?>

<main>
    <section> 
        <?php
            if(isset($_SESSION['userId'])){
                #initialize DB connection
                $conn = $Utilities->getDBConnection();

                #select all photos registered in DB
                $sql_photo_filenames = "SELECT name FROM photos";
                $stmt_photo_filenames = $conn->stmt_init();

                #get photo data from DB
                $sql_photo_data = "SELECT name, description, tag FROM photos";
                $stmt_photo_data = $conn->stmt_init();

                #get all preset tags from DB
                $sql_tags = "SELECT tag FROM ref_tags";
                $stmt_tags = $conn->stmt_init();

                #check for SQL errors
                if(!$stmt_photo_filenames->prepare($sql_photo_filenames)){
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();
                } elseif(!$stmt_photo_data->prepare($sql_photo_data)){
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();              
                } elseif(!$stmt_tags->prepare($sql_tags)) {
                    header("Location: ../photoalbum.php?error=sqlerror");
                    exit();
                } else {
                    #if no SQL errors, execute and store results
                    $stmt_photo_filenames->execute();
                    $stmt_photo_filenames->store_result();
                    $stmt_photo_data->execute();
                    $photo_data_result = $stmt_photo_data->get_result();
                   #$photo_data_rows = $stmt_photo_filenames->num_rows();  -not currently needed
                    $stmt_tags->execute();
                    $tags_result = $stmt_tags->get_result();
                }  
                ?>

                <div class="album-layout">
                    <form action="#" method="post">
                        <input list="set_tag" name="uid">
                        <datalist id="set_tag">';
                        <?php
                            #create the tag filter menu
                            while($tag = $tags_result->fetch_array()){
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

                        #create all the photos registered in the DB
                        while($photo = $photo_data_result->fetch_array()){

                            if($photo['tag'] == "#ALL"){
                                echo    '<div class="album-photo">
                                            <a target="_blank" href="./photos/'.$photo['name'].'">
                                                <img title="'.$photo['description'].'" src=./photos/'.$photo['name'].' loading="lazy">
                                            </a>
                                        </div>';
                            }
                        }
                        $conn->close();
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
    </body>
</html>

