<?php

session_start();

include "../Model/DatabaseConnection.php";


/* =========================
   LOGIN CHECK
========================= */

if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../View/login.php");
    exit();
}


/* =========================
   ROLE CHECK
========================= */

if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Journalist"
) {
    header("Location: ../View/login.php");
    exit();
}


/* =========================
   DATABASE
========================= */

$database = new DatabaseConnection();
$connection = $database->openConnection();


/* =========================
   CURRENT JOURNALIST
========================= */

$journalistResult =
    $database->getUserById(
        $connection,
        $_SESSION["userId"]
    );


if (
    !$journalistResult ||
    $journalistResult->num_rows == 0
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}


$journalist =
    $journalistResult->fetch_assoc();


if (
    $journalist["role"] != "Journalist" ||
    $journalist["status"] != "Active"
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}


$journalistId =
    (int)$journalist["id"];

$journalistName =
    $journalist["fullname"];


/* Update normal login session */

$_SESSION["userName"] =
    $journalist["fullname"];

$_SESSION["userEmail"] =
    $journalist["email"];

$_SESSION["userRole"] =
    $journalist["role"];


/* =========================
   COVER / UNCOVER
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId =
        (int)($_POST["post_id"] ?? 0);

    $action =
        $_POST["action"] ?? "";


    if ($postId > 0) {

        /*
            Journalist may cover only
            an Approved post.
        */

        $postIsApproved =
            $database->isPostInStatus(
                $connection,
                $postId,
                "Approved"
            );


        if ($postIsApproved) {

            if ($action == "cover") {

                $database->addCoverage(
                    $connection,
                    $postId,
                    $journalistId
                );

            }


            elseif ($action == "uncover") {

                $database->removeCoverage(
                    $connection,
                    $postId,
                    $journalistId
                );

            }

        }

    }


    $connection->close();

    header("Location: ../View/journalist.php");
    exit();

}


/* =========================
   GET POSTS
========================= */

$postsResult =
    $database->getAllPosts(
        $connection
    );


/* =========================
   POST INFORMATION FOR VIEW
========================= */

$journalistPostInfo = array();

if ($postsResult && $postsResult->num_rows > 0) {

    while ($postData = $postsResult->fetch_assoc()) {

        if ($postData["status"] != "Approved") {
            continue;
        }

        $ownerName = "Anonymous User";

        if ((int)$postData["anonymous"] == 0) {

            $ownerResult = $database->getUserById(
                $connection,
                $postData["user_id"]
            );

            if ($ownerResult && $ownerResult->num_rows > 0) {
                $owner = $ownerResult->fetch_assoc();
                $ownerName = $owner["fullname"];
            }
        }

        $covered = $database->hasJournalistCoverage(
            $connection,
            $postData["id"],
            $journalistId
        );

        $journalistPostInfo[(int)$postData["id"]] = array(
            "ownerName" => $ownerName,
            "covered" => $covered
        );
    }

    $postsResult->data_seek(0);
}
