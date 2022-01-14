<?php
    session_start();
    if (isset($_SESSION['userId'])){
         if(isset($_POST['add-link'])){

            $id = $_SESSION['Id'];
            $category = $_POST['category'];
            $description = $_POST['description'];
            $link = $_POST['link'];

            if(empty($category) || empty($description) || empty($link) || strpos($_POST['description'], "Description...") == true || strpos($_POST['link'], "Link...") == true){
                header("Location: ../links.php?error=emptyfields");
                exit();
            } 
            if((!(substr($link, 0, 7) == 'http://')) && (!(substr($link, 0, 8) == 'https://'))){
                header("Location: ../links.php?error=malformedURL");
                exit();
            } else {
                require 'dbhandler.inc.php';
                $DB = new dbHandler();
                $conn = $DB->getDBConnection();
                $DB = null;
            
                $sql_categorycheck = "SELECT category FROM ref_link_categories WHERE category=?";
                $stmt_categorycheck = $conn->stmt_init();

                if(!$stmt_categorycheck->prepare($sql_categorycheck)){
                    header("Location: ../adminpanel.php?error=PREPAREUIDsqlerror");
                    exit();
                } else {
                    $stmt_categorycheck->bind_param("s", $category);
                    $stmt_categorycheck->execute();
                    $stmt_categorycheck->store_result();
                    $resultCategoryCheck = $stmt_categorycheck->num_rows();

                    if ($resultCategoryCheck == 0){
                        $sql_addCategory = "INSERT INTO ref_link_categories (category) VALUES (?)";
                        $stmt_addCategory = $conn->stmt_init();

                        if(!$stmt_addCategory->prepare($sql_addCategory)){
                            header("Location: ../adminpanel.php?error=sqlerror");
                            $conn->close();
                            exit(); 
                        } else {
                            $stmt_addCategory->bind_param("s", $category);
                            $stmt_addCategory->execute();
                        }
                    } 

                    $sql_addLink = "INSERT INTO links (account, category, description, link) VALUES (?, ?, ?, ?)";
                    $stmt_addLink = $conn->stmt_init();

                    if(!$stmt_addLink->prepare($sql_addLink)){
                        header("Location: ../adminpanel.php?error=sqlerror");
                        $conn->close();
                        exit(); 
                    } else {
                        $stmt_addLink->bind_param("ssss", $id, $category, $description, $link);
                        $stmt_addLink->execute();
                        header("Location: ../links.php?info=addedLink");
                    }  
                }
            }
        }
    } else {
         #log some shit
    }