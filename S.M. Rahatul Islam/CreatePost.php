<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Citizen"
) {
    header("Location: ../utsa_Mazumdar/login.php");
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


    /* Check upload error */

    if ($file["error"] != UPLOAD_ERR_OK) {

        $errorMessage =
            "There was a problem uploading the file.";

        return false;
    }


    /* Get file extension */

    $extension = strtolower(
        pathinfo(
            $file["name"],
            PATHINFO_EXTENSION
        )
    );


    /* Check allowed extension */

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


    /*
        Path stored in database:

        uploads/file_name.jpg

        Actual folder is:

        Group_3_WebTech_Sec_W/uploads/
    */

    $databasePath =
        "uploads/" . $fileName;

    $serverPath =
        "../uploads/" . $fileName;


    /* Save uploaded file */

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


/* =========================
   CREATE POST
   ========================= */

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


    /* Anonymous checkbox */

    $anonymous = 0;

    if (isset($_POST["anonymous"])) {
        $anonymous = 1;
    }


    /* Check required fields */

    if (
        $postType == "" ||
        $title == "" ||
        $description == ""
    ) {

        $errorMessage =
            "Please complete all required fields.";

    }


    /* Check valid post type */

    elseif (
        $postType != "Normal Post" &&
        $postType != "Emergency Post"
    ) {

        $errorMessage =
            "Please select a valid post type.";

    }


    /* Emergency posts cannot be anonymous */

    elseif (
        $postType == "Emergency Post" &&
        $anonymous == 1
    ) {

        $errorMessage =
            "Emergency posts cannot be anonymous.";

    }


    /* Check uploads folder */

    elseif (!is_dir("../uploads")) {

        $errorMessage =
            "Uploads folder was not found.";

    }


    else {


        /* =========================
           SAVE PHOTO
           ========================= */

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


        /*
            false means an upload
            error occurred.

            null means no file was
            uploaded, which is allowed.
        */

        if ($photoPath !== false) {


            /* =========================
               SAVE VIDEO
               ========================= */

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


                /* =========================
                   DATABASE
                   ========================= */

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


                /* Post created successfully */

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

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CivicLens - Create Post</title>

    <link
        rel="stylesheet"
        href="CSS/CreatePost.css"
    >

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Create Post</p>

    </div>

    <a
        href="UserNewsfeed.php"
        class="backTop"
    >
        Back to Newsfeed
    </a>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">

            <div class="avatar">

                <?php
                echo strtoupper(
                    substr(
                        $userName,
                        0,
                        1
                    )
                );
                ?>

            </div>


            <div>

                <small>
                    Signed in as
                </small>

                <strong>

                    <?php
                    echo htmlspecialchars(
                        $userName
                    );
                    ?>

                </strong>

            </div>

        </div>


        <div class="menu">

            <a href="UserNewsfeed.php">
                Newsfeed
            </a>

            <a href="Profile.php">
                Profile
            </a>

            <a href="PendingPosts.php">
                Pending Posts
            </a>

            <a href="ShowCases.php">
                Show Cases
            </a>

            <a href="Donation.php">
                Donation
            </a>

        </div>


        <a
            href="../utsa_Mazumdar/logout.php"
            class="logout"
        >
            Logout
        </a>

    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <h2>Create New Post</h2>

            <p>
                Share a civic issue or important community information.
            </p>


            <?php if ($errorMessage != "") { ?>

                <p>
                    <?php
                    echo htmlspecialchars(
                        $errorMessage
                    );
                    ?>
                </p>

            <?php } ?>

        </div>



        <div class="formCard">

            <form
                action="CreatePost.php"
                method="post"
                enctype="multipart/form-data"
            >


                <div class="formGroup">

                    <label>
                        Post Type
                    </label>

                    <select
                        name="postType"
                        required
                    >

                        <option value="">
                            Select Post Type
                        </option>

                        <option value="Normal Post">
                            Normal Post
                        </option>

                        <option value="Emergency Post">
                            Emergency Post
                        </option>

                    </select>

                    <small>
                        Emergency posts should only be used for urgent situations.
                    </small>

                </div>



                <div class="formGroup">

                    <label>
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter post title"
                        required
                    >

                </div>



                <div class="formGroup">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Describe the issue clearly"
                        required
                    ></textarea>

                </div>



                <div class="formGroup">

                    <label>
                        Contact Info
                    </label>

                    <input
                        type="text"
                        name="contactInfo"
                        placeholder="Phone number or other contact information"
                    >

                </div>



                <div class="locationTitle">

                    <h3>
                        Location Information
                    </h3>

                    <p>
                        Add location details so the issue can be identified easily.
                    </p>

                </div>


                <div class="locationGrid">


                    <div class="formGroup">

                        <label>
                            Division
                        </label>

                        <select name="division">

                            <option value="">
                                Select Division
                            </option>

                            <option>Dhaka</option>

                            <option>
                                Chattogram
                            </option>

                            <option>Khulna</option>

                            <option>
                                Rajshahi
                            </option>

                            <option>
                                Rangpur
                            </option>

                            <option>
                                Mymensingh
                            </option>

                            <option>
                                Sylhet
                            </option>

                            <option>
                                Barishal
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Zila
                        </label>

                        <select name="zila">

                            <option value="">
                                Select Zila
                            </option>

                            <option>
                                Zila 1
                            </option>

                            <option>
                                 Zila 2
                            </option>

                            <option>
                                 Zila 3
                            </option>

                            <option>
                                 Zila 4
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Upazila
                        </label>

                        <select name="upazila">

                            <option value="">
                                Select Upazila
                            </option>

                            <option>
                                Upazila 1
                            </option>

                            <option>
                                Upazila 2
                            </option>

                            <option>
                                Upazila 3
                            </option>

                            <option>
                               Upazila 4
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Union / Municipality
                        </label>

                        <select name="union">

                            <option value="">
                                Select Union / Municipality
                            </option>

                            <option>
                                Union-1
                            </option>

                            <option>
                                Union-2
                            </option>

                            <option>
                                Municipality-1
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup fullWidth">

                        <label>
                            Area
                        </label>

                        <select name="area">

                            <option value="">
                                Select Area
                            </option>

                            <option>
                                Area-1
                            </option>

                            <option>
                                Area-2
                            </option>

                            <option>
                                Area-3
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>


                </div>



                <div class="anonymousBox">

                    <input
                        type="checkbox"
                        name="anonymous"
                        id="anonymous"
                    >

                    <label for="anonymous">
                        Post as Anonymous
                    </label>

                </div>


                <p class="emergencyNote">
                    Anonymous posting is not allowed for Emergency Posts.
                </p>



                <div class="mediaTitle">

                    <h3>
                        Add Media
                    </h3>

                    <p>
                        Photo and video are optional.
                    </p>

                </div>



                <div class="mediaGrid">


                    <div class="formGroup">

                        <label>
                            Upload Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.bmp,.gif"
                        >

                    </div>



                    <div class="formGroup">

                        <label>
                            Upload Video
                        </label>

                        <input
                            type="file"
                            name="video"
                            accept=".mp4,.avi,.mov,.wmv,.mkv"
                        >

                    </div>


                </div>



                <div class="formButtons">

                    <button
                        type="submit"
                        class="createButton"
                    >
                        Create Post
                    </button>

                    <a
                        href="UserNewsfeed.php"
                        class="cancelButton"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>


    </main>


</div>


</body>

</html>