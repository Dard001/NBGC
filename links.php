<?php
    require "meta.php";
    require "header.php";
?>

        <main>
            <section> 
                <?php
                if(isset($_SESSION['userId'])){
                        $conn = $Utilities->getDBConnection();
                        $sql_categories = "SELECT category FROM ref_link_categories";
                        $stmt_categories = $conn->stmt_init();

                        if(!$stmt_categories->prepare($sql_categories)){
                            header("Location: ../links.php?error=PREPAREUIDsqlerror");
                            exit();
                        }

                        $stmt_categories->execute();
                        $result_categories = $stmt_categories->get_result();  
                        $fetched_categories = $result_categories->fetch_all();
                ?>

                <div class="links-layout"> 
                    <div class="links-column">
                        <p class="category-title">Add A link</p>
                                <form action="./includes/addlink.inc.php" method="post">
                                    Set Category: 
                                    <input type="text" list="set-category" name="category" placeholder="Category...">
                                    <datalist id="set-category">';
                                    <?php

                                        foreach($fetched_categories as $set_category){
                                            echo '<option value="'.$set_category[0].'" />';
   
                                        }

                                    ?>
                                    </datalist>
                                    <input type="text" name="description" placeholder="Description...">
                                    <input type="text" name="link" placeholder="Link...">
                                    <input type="submit" name="add-link" value="Add Link">
                                </form> 
                    </div>
                    <div class="links-column">

                    <?php
                    
                        foreach($fetched_categories as $category){
                            $titlehtml = '<div class="category-title">';
                            $titlehtml.= $category[0];
                            $titlehtml.='</div>';
                           
                            $sql_links = "SELECT id, description, link FROM links WHERE category = ?";
                            $stmt_links = $conn->stmt_init();

                            if(!$stmt_links->prepare($sql_links)){
                                header("Location: ../links.php?error=PREPAREUIDsqlerror");
                                exit();
                            }
                            $stmt_links->bind_param("s", $category[0]);
                            $stmt_links->execute();
                            $result_links = $stmt_links->get_result();
                            $fetched_links = $result_links->fetch_all(MYSQLI_ASSOC);
                            $linkhtml = '';
                            foreach($fetched_links as $link){
                                    $linkhtml .= '<div class="link-layout">';
                                    $linkhtml .= '<div class="link-description">';
                                    $linkhtml .=  $link['description'];
                                    $linkhtml .= '</div>';
                                    $linkhtml .=  '<div class="link-url">';
                                    $linkhtml .=  '<a target="_blank" href="'.$link['link'].'">'.$link['link'].'</a>';
                                    $linkhtml .=  '</div>';
                                    $linkhtml .= '<div class="link-remove">X';
                                    $linkhtml .= '</div></div>';
                            }
                            echo $titlehtml;
                            echo $linkhtml;
                            
                        }
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