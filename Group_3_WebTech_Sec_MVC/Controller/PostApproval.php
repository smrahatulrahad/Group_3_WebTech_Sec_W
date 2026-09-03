<?php

session_start();

include "../Model/DatabaseConnection.php";


// Check login
if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../View/login.php");
    exit();
}


// Check Admin or Moderator
if (
    $_SESSION["userRole"] != "Admin" &&
    $_SESSION["userRole"] != "Moderator"
) {
    header("Location: ../View/login.php");
    exit();
}


$database = new DatabaseConnection();
$connection = $database->openConnection();


// Get current user
$userResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);

if ($userResult->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}

$user = $userResult->fetch_assoc();


// Check user status
if (
    $user["status"] == "Disabled" ||
    ($user["role"] != "Admin" && $user["role"] != "Moderator")
) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}


$userId = $user["id"];
$userName = $user["fullname"];
$userRole = $user["role"];


// Keep current user information in session
$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $user["email"];


// Approve or Reject post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = (int)($_POST["postId"] ?? 0);
    $decision = $_POST["decision"] ?? "";

    if (
        $postId > 0 &&
        ($decision == "Approve" || $decision == "Reject")
    ) {

        // Convert button value to database status
        if ($decision == "Approve") {
            $status = "Approved";
        } else {
            $status = "Rejected";
        }


        // Check if post is still Pending
        $postIsPending =
            $database->isPostInStatus(
                $connection,
                $postId,
                "Pending"
            );


        if ($postIsPending) {

            // Update post status
            $updated = $database->updatePostStatus(
                $connection,
                $postId,
                $status,
                $userId
            );

            $connection->close();


            if ($updated) {

                if ($status == "Approved") {
                    header("Location: ../View/PostApproval.php?message=approved");
                } else {
                    header("Location: ../View/PostApproval.php?message=rejected");
                }

            } else {

                header("Location: ../View/PostApproval.php?message=error");

            }

            exit();

        }

    }


    $connection->close();

    header("Location: ../View/PostApproval.php?message=notfound");
    exit();
}


// Message
$message = "";

$messageType = $_GET["message"] ?? "";

if ($messageType == "approved") {

    $message = "Post approved successfully.";

} elseif ($messageType == "rejected") {

    $message = "Post rejected successfully.";

} elseif ($messageType == "notfound") {

    $message = "The selected post was not found or is no longer pending.";

} elseif ($messageType == "error") {

    $message = "The post could not be updated. Please try again.";
}


// Search
$searchText = trim($_GET["search"] ?? "");


// Get pending posts
$result = $database->getPendingPosts($connection);

$pendingPosts = [];

while ($row = $result->fetch_assoc()) {

    if ($searchText != "") {

        $searchData =
            $row["title"] . " " .
            $row["description"] . " " .
            $row["fullname"] . " " .
            $row["post_type"];

        if (stripos($searchData, $searchText) === false) {
            continue;
        }
    }

    $pendingPosts[] = $row;
}


// Select one post for review
$selectedPostId = (int)($_GET["post"] ?? 0);

$selectedPost = null;


if ($selectedPostId > 0) {

    $result =
        $database->getPostByIdAndStatus(
            $connection,
            $selectedPostId,
            "Pending"
        );


    if ($result->num_rows > 0) {

        $selectedPost = $result->fetch_assoc();

    } else {

        $message = "The selected post was not found or is no longer pending.";

    }
}


$connection->close();

$pendingCount = count($pendingPosts);
