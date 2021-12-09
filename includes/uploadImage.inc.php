<?php
    session_start();
    $photo_dir = $_SERVER['DOCUMENT_ROOT']."/NBGC/photos/";
    $target_file = $photo_dir . basename($_FILES['image-file']['name']);
    $uploadOk = 1;
    $filetype = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    #$filename= htmlspecialchars(basename( $_FILES['image-file']['name']));
    #$name = strstr($filename, '.', true);
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
                error_log("File is not an image");
                $uploadOk = 0;
            }
        }

        if (file_exists($target_file)){
            header("Location: ../photoalbum.php?error=fileexists");
            error_log("File already exists");
            $uploadOk = 0;
        }

        if ($_FILES["image-file"]["size"] > 5000000){
            header("Location: ../photoalbum.php?error=filetolarge");
            error_log("File too large");
            $uploadOk = 0;        
        }

        if($filetype != "jpg") {
          header("Location: ../photoalbum.php?error=filenotjpg=".$filetype);
          error_log("Sorry, only JPG files are allowed");
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
            $uid = $_SESSION['userId'];

            if(!$stmt->prepare($sql)){
                header("Location: ../photoalbum.php?error=sqlerror");
                $conn->close();
                exit(); 
            } else {
                $stmt->bind_param("sss", $name, $description, $uid);
                $stmt->execute();

                if (move_uploaded_file($_FILES['image-file']['tmp_name'], $photo_dir.$name.".".$filetype)) {
                    header("Location: ../photoalbum.php?fileuploaded=".$name.".jpg");
                    error_log("FILE UPLOADED!");

                  } else {
                    header("Location: ../photoalbum.php?error=othererror");
                    error_log("Something went wrong");
                  }
            }
        }
    } else {
        echo 'Ixnay on the hombre.';
    }
