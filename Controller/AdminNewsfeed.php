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

    header("Location: login.php");
    exit();

}


/* =========================
   ROLE CHECK
========================= */

if (
    !isset($_SESSION["userRole"]) ||
    (
        $_SESSION["userRole"] != "Admin" &&
        $_SESSION["userRole"] != "Moderator"
    )
) {

    header("Location: login.php");
    exit();

}


/* =========================
   DATABASE
========================= */

$database = new DatabaseConnection();

$connection = $database->openConnection();


/* =========================
   CURRENT USER
========================= */

$userResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if (
    !$userResult ||
    $userResult->num_rows == 0
) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}


$currentUser = $userResult->fetch_assoc();


if ($currentUser["status"] != "Active") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}


$userName = $currentUser["fullname"];
$userRole = $currentUser["role"];


$_SESSION["userName"] = $userName;
$_SESSION["userEmail"] = $currentUser["email"];
$_SESSION["userRole"] = $userRole;


/* =========================
   MOVE TO TRASH / RESTORE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = (int)($_POST["post_id"] ?? 0);

    $action = $_POST["action"] ?? "";


    if ($postId > 0) {


        /* Move post to Trash */

        if ($action == "trash") {

            $newStatus = "Rejected";

            $database->updatePostStatus(
                $connection,
                $postId,
                $newStatus,
                $_SESSION["userId"]
            );


            header(
                "Location: AdminNewsfeed.php?view=trash"
            );

            exit();

        }


        /* Restore post */

        elseif ($action == "restore") {

            $newStatus = "Pending";

            $database->restorePost(
                $connection,
                $postId
            );


            header(
                "Location: AdminNewsfeed.php?view=all"
            );

            exit();

        }

    }

}


/* =========================
   VIEW
========================= */

$view = $_GET["view"] ?? "all";


if (
    $view != "all" &&
    $view != "trash"
) {

    $view = "all";

}


/* =========================
   SEARCH
========================= */

$search = trim(
    $_GET["search"] ?? ""
);


/* =========================
   POSTS
========================= */

$postsResult = $database->getAllPosts(
    $connection
);


/* =========================
   POST INFORMATION FOR VIEW
========================= */

$adminPostInfo = array();

if ($postsResult && $postsResult->num_rows > 0) {

    while ($postData = $postsResult->fetch_assoc()) {

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

        $case = null;

        $caseResult = $database->getPoliceCaseDetails(
            $connection,
            $postData["id"]
        );

        if ($caseResult->num_rows > 0) {
            $case = $caseResult->fetch_assoc();
        }

        $coverageRows = array();

        $coverageResult = $database->getJournalistCoverageByPost(
            $connection,
            $postData["id"]
        );

        while ($coverage = $coverageResult->fetch_assoc()) {
            $coverageRows[] = $coverage;
        }

        $adminPostInfo[(int)$postData["id"]] = array(
            "ownerName" => $ownerName,
            "case" => $case,
            "coverage" => $coverageRows
        );
    }

    $postsResult->data_seek(0);
}
