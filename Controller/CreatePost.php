<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: login.php");
    exit();
}


if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Citizen"
) {
    header("Location: login.php");
    exit();
}


$userId = $_SESSION["userId"];
$userName = $_SESSION["userName"];

$errorMessage = "";



   //FILE UPLOAD FUNCTION
  

function saveUploadedFile(
    $file,
    $allowedExtensions,
    $prefix,
    &$errorMessage
) {

    /* File is optional */

    if (
        !isset($file) ||
        $file["error"] == UPLOAD_ERR_NO_FILE
    ) {
        return null;
    }


    

    if ($file["error"] != UPLOAD_ERR_OK) {

        $errorMessage =
            "There was a problem uploading the file.";

        return false;
    }


    

    $extension = strtolower(
        pathinfo(
            $file["name"],
            PATHINFO_EXTENSION
        )
    );


    

    if (
        !in_array(
            $extension,
            $allowedExtensions
        )
    ) {

        $errorMessage =
            "Invalid file type.";

        return false;
    }


    /* Create a unique file name */

    $fileName =
        $prefix . "_" .
        time() . "_" .
        uniqid() . "." .
        $extension;


    

    $databasePath =
        "uploads/" . $fileName;

    $serverPath =
        "../uploads/" . $fileName;


    // Save uploaded file 

    if (
        !move_uploaded_file(
            $file["tmp_name"],
            $serverPath
        )
    ) {

        $errorMessage =
            "Could not save the uploaded file.";

        return false;
    }


    return $databasePath;
}



   //CREATE POST
   

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    /* Get form information */

    $postType =
        trim($_POST["postType"] ?? "");

    $title =
        trim($_POST["title"] ?? "");

    $description =
        trim($_POST["description"] ?? "");

    $contactInfo =
        trim($_POST["contactInfo"] ?? "");

    $division =
        trim($_POST["division"] ?? "");

    $zila =
        trim($_POST["zila"] ?? "");

    $upazila =
        trim($_POST["upazila"] ?? "");

    $union =
        trim($_POST["union"] ?? "");

    $area =
        trim($_POST["area"] ?? "");


    // Anonymous checkbox 

    $anonymous = 0;

    if (isset($_POST["anonymous"])) {
        $anonymous = 1;
    }


    // Check required fields 

    if (
        $postType == "" ||
        $title == "" ||
        $description == ""
    ) {

        $errorMessage =
            "Please complete all required fields.";

    }


    // Check valid post type 

    elseif (
        $postType != "Normal Post" &&
        $postType != "Emergency Post"
    ) {

        $errorMessage =
            "Please select a valid post type.";

    }


    // Emergency posts cannot be anonymous 

    elseif (
        $postType == "Emergency Post" &&
        $anonymous == 1
    ) {

        $errorMessage =
            "Emergency posts cannot be anonymous.";

    }


    // Check uploads folder 

    elseif (!is_dir("../uploads")) {

        $errorMessage =
            "Uploads folder was not found.";

    }


    else {


       

        $photoExtensions = [
            "jpg",
            "jpeg",
            "png",
            "bmp",
            "gif"
        ];


        $photoPath = saveUploadedFile(
            $_FILES["photo"] ?? null,
            $photoExtensions,
            "photo",
            $errorMessage
        );


       

        if ($photoPath !== false) {


           

            $videoExtensions = [
                "mp4",
                "avi",
                "mov",
                "wmv",
                "mkv"
            ];


            $videoPath = saveUploadedFile(
                $_FILES["video"] ?? null,
                $videoExtensions,
                "video",
                $errorMessage
            );


            if ($videoPath !== false) {


                
                  // DATABASE
                  

                $database =
                    new DatabaseConnection();

                $connection =
                    $database->openConnection();


                $created =
                    $database->createPost(
                        $connection,
                        $userId,
                        $postType,
                        $title,
                        $description,
                        $contactInfo,
                        $division,
                        $zila,
                        $upazila,
                        $union,
                        $area,
                        $anonymous,
                        $photoPath,
                        $videoPath
                    );


                $connection->close();



                if ($created) {

                    header(
                        "Location: PendingPosts.php"
                    );

                    exit();

                }


                else {

                    $errorMessage =
                        "Post could not be created. Please try again.";

                }

            }

        }

    }

}
