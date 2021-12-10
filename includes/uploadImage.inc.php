<?php
    session_start();
    $photo_dir = $_SERVER['DOCUMENT_ROOT']."/NBGC/photos/";
    $target_file = $photo_dir . basename($_FILES['image-file']['name']);
    $uploadOk = 1;
    $filetype = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    $name = rand(10000, 100000000000);
    $description = $_POST['image-description'];
    
    if (isset($_SESSION['userId'])){
        
        if(isset($_POST['upload-image'])){
            $check = getimagesize($_FILES['image-file']['tmp_name']);
            if($check !== false){
                error_log("File is an image - ".$check['mime']);
                $uploadOk = 1;
            } else {
                header("Location: ../photoalbum.php?error=notanimage");
                $uploadOk = 0;
            }
        }

        if (file_exists($target_file)){
            header("Location: ../photoalbum.php?error=fileexists");
            $uploadOk = 0;
        }

        if ($_FILES["image-file"]["size"] > 5000000){
            header("Location: ../photoalbum.php?error=filetolarge");
            $uploadOk = 0;        
        }

        if($filetype != "jpg" && $filetype != "jpeg" && $filetype != "png" && $filetype != "gif") {
          header("Location: ../photoalbum.php?error=wrongfiletype=".$filetype);
          $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            exit();
        } else {
            require 'dbhandler.inc.php';
            $DB = new dbHandler();
            $conn = $DB->getDBConnection();
            $DB = null;
            $sql = "INSERT INTO photos (name, description, uid) VALUES (?, ?, ?)";
            $stmt = $conn->stmt_init();
            $id = $_SESSION['Id'];

            if(!$stmt->prepare($sql)){
                header("Location: ../photoalbum.php?error=sqlerror");
                $conn->close();
                exit(); 
            } else {
                if (move_uploaded_file($_FILES['image-file']['tmp_name'], $photo_dir.$name.".".$filetype)) {
                    $name .= ".".$filetype;
                    $stmt->bind_param("sss", $name, $description, $id);
                    $stmt->execute();
                    header("Location: ../photoalbum.php?fileuploaded=".$name);

                  } else {
                    header("Location: ../photoalbum.php?error=othererror");
                    error_log("Something went wrong");
                  }
            }
        }
    } else {
        #log some shit
    }
