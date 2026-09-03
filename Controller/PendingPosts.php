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
$successMessage = "";



  // DATABASE CONNECTION
 

$database = new DatabaseConnection();

$connection = $database->openConnection();




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId =
        (int) ($_POST["postId"] ?? 0);

    $title =
        trim($_POST["title"] ?? "");

    $category =
        trim($_POST["category"] ?? "");

    $body =
        trim($_POST["body"] ?? "");


    /* Validation */

    if (
        $postId <= 0 ||
        $title == "" ||
        $body == ""
    ) {

        $errorMessage =
            "Please complete all required fields.";

    }


    elseif (
        $category != "Normal Post" &&
        $category != "Emergency Post"
    ) {

        $errorMessage =
            "Please select a valid post type.";

    }


    else {


        
           // Citizen can update only:

        

        $removeAnonymous = false;


        if ($category == "Emergency Post") {

            $removeAnonymous = true;

        }


        $updateResult =
            $database->updatePendingPostByUser(
                $connection,
                $postId,
                $userId,
                $title,
                $category,
                $body,
                $removeAnonymous
            );


        if ($updateResult !== false) {


            if ($updateResult > 0) {


                $connection->close();


                header(
                    "Location: PendingPosts.php?saved=1"
                );

                exit();

            }


            else {


                $errorMessage =
                    "Post was not changed or is no longer pending.";

            }

        }


        else {


            $errorMessage =
                "Post could not be updated. Please try again.";

        }



    }

}



  // SUCCESS MESSAGE
   

if (isset($_GET["saved"])) {

    $successMessage =
        "Post updated successfully.";

}



$result =
    $database->getPostsByUser(
        $connection,
        $userId
    );


$pendingPosts = [];


while ($row = $result->fetch_assoc()) {


    if ($row["status"] == "Pending") {

        $pendingPosts[] = $row;

    }

}




$selectedPostId =
    (int) ($_GET["post"] ?? 0);


$selectedPost = null;


if ($selectedPostId > 0) {


    foreach ($pendingPosts as $post) {


        if (
            (int) $post["id"] ==
            $selectedPostId
        ) {

            $selectedPost = $post;

            break;

        }

    }


    if ($selectedPost === null) {

        $errorMessage =
            "The selected post was not found or is no longer pending.";

    }

}


$connection->close();
