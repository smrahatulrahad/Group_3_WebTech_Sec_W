<?php

session_start();

include "../Model/DatabaseConnection.php";


// Check login
if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: login.php");
    exit();
}


// Check Admin or Moderator
if (
    $_SESSION["userRole"] != "Admin" &&
    $_SESSION["userRole"] != "Moderator"
) {
    header("Location: login.php");
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

    header("Location: login.php");
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

    header("Location: login.php");
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
        $sql = "SELECT id FROM posts
                WHERE id = ?
                AND status = 'Pending'";

        $statement = $connection->prepare($sql);
        $statement->bind_param("i", $postId);
        $statement->execute();

        $result = $statement->get_result();


        if ($result->num_rows > 0) {

            // Update post status
            $updated = $database->updatePostStatus(
                $connection,
                $postId,
                $status,
                $userId
            );

            $statement->close();
            $connection->close();


            if ($updated) {

                if ($status == "Approved") {
                    header("Location: PostApproval.php?message=approved");
                } else {
                    header("Location: PostApproval.php?message=rejected");
                }

            } else {

                header("Location: PostApproval.php?message=error");

            }

            exit();

        }


        $statement->close();
    }


    $connection->close();

    header("Location: PostApproval.php?message=notfound");
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

    $sql = "SELECT posts.*, users.fullname
            FROM posts
            JOIN users ON posts.user_id = users.id
            WHERE posts.id = ?
            AND posts.status = 'Pending'";

    $statement = $connection->prepare($sql);

    $statement->bind_param("i", $selectedPostId);

    $statement->execute();

    $result = $statement->get_result();


    if ($result->num_rows > 0) {

        $selectedPost = $result->fetch_assoc();

    } else {

        $message = "The selected post was not found or is no longer pending.";

    }

    $statement->close();
}


$connection->close();

$pendingCount = count($pendingPosts);

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CivicLens - Post Approval</title>

    <link
        rel="stylesheet"
        href="CSS/PostApproval.css"
    >

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Post Approval</p>

    </div>


    <a
        href="../Adnan Raad/AdminNewsfeed.php"
        class="backTop"
    >
        Back to Admin Newsfeed
    </a>

</header>


<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">


            <div class="avatar">

                <?php

                echo strtoupper(
                    substr($userName, 0, 1)
                );

                ?>

            </div>


            <div>

                <small>Signed in as</small>

                <strong>

                    <?php

                    echo htmlspecialchars($userName);

                    ?>

                </strong>


                <span>

                    <?php

                    echo htmlspecialchars($userRole);

                    ?>

                </span>

            </div>


        </div>


        <div class="menu">

            <a href="../Adnan Raad/AdminNewsfeed.php">
                Newsfeed
            </a>

            <a href="../S.M. Rahatul Islam/ShowCases.php">
                Case Status
            </a>

            <a
                href="PostApproval.php"
                class="active"
            >
                Post Approval
            </a>

            <a href="StaffManagement.php">
                Staff Management
            </a>

            <a href="../Adnan Raad/UserManagement.php">
                User Management
            </a>

        </div>


        <a
            href="logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>


    <main class="mainContent">


        <div class="pageTitle">

            <h2>Post Approval</h2>

            <p>
                Review pending posts before they appear on the public newsfeed.
            </p>

        </div>


        <?php if ($message != "") { ?>

            <div class="message">

                <?php

                echo htmlspecialchars($message);

                ?>

            </div>

        <?php } ?>


        <div class="topTools">


            <form
                class="searchBox"
                action="PostApproval.php"
                method="get"
            >

                <input
                    type="text"
                    name="search"
                    placeholder="Search pending posts..."
                    value="<?php echo htmlspecialchars($searchText); ?>"
                >

                <button type="submit">
                    Search
                </button>

            </form>


            <a
                href="PostApproval.php"
                class="refreshButton"
            >
                Refresh
            </a>


        </div>


        <div class="contentGrid">


            <section class="pendingSection">


                <div class="sectionTitle">

                    <h3>Pending Posts</h3>

                    <span>

                        <?php

                        echo $pendingCount;

                        echo $pendingCount == 1
                            ? " Post"
                            : " Posts";

                        ?>

                    </span>

                </div>


                <?php if ($pendingCount == 0) { ?>

                    <div class="emptyDetails">

                        <h4>No Pending Posts</h4>

                        <p>
                            No pending posts were found.
                        </p>

                    </div>

                <?php } ?>


                <?php foreach ($pendingPosts as $post) { ?>


                    <?php

                    if ((int)$post["anonymous"] == 1) {

                        $authorName = "Anonymous";

                    } else {

                        $authorName = $post["fullname"];

                    }


                    $createdAt = date(
                        "d M Y, h:i A",
                        strtotime($post["created_at"])
                    );


                    $isEmergency =
                        $post["post_type"] == "Emergency Post";

                    ?>


                    <div
                        class="postItem<?php echo $isEmergency ? " emergencyItem" : ""; ?>"
                    >


                        <div>

                            <h4>

                                <?php

                                echo htmlspecialchars(
                                    $post["title"]
                                );

                                ?>

                            </h4>


                            <p>

                                <?php

                                echo htmlspecialchars(
                                    $authorName
                                );

                                ?>

                            </p>


                            <small>

                                <?php

                                echo htmlspecialchars(
                                    $createdAt
                                );

                                ?>

                            </small>

                        </div>


                        <a
                            href="PostApproval.php?post=<?php echo (int)$post["id"]; ?>&search=<?php echo urlencode($searchText); ?>"
                        >
                            Review
                        </a>


                    </div>


                <?php } ?>


            </section>


            <section class="detailsSection">


                <div class="sectionTitle">

                    <h3>Post Details</h3>

                </div>


                <?php if ($selectedPost === null) { ?>

                    <div class="emptyDetails">

                        <h4>Select a pending post</h4>

                        <p>
                            Click Review to see the full post before approving or rejecting it.
                        </p>

                    </div>

                <?php } ?>


                <?php if ($selectedPost !== null) { ?>


                    <?php

                    if ((int)$selectedPost["anonymous"] == 1) {

                        $selectedAuthor = "Anonymous";

                    } else {

                        $selectedAuthor =
                            $selectedPost["fullname"];

                    }


                    $selectedCreatedAt = date(
                        "d M Y, h:i A",
                        strtotime(
                            $selectedPost["created_at"]
                        )
                    );

                    ?>


                    <form
                        action="PostApproval.php"
                        method="post"
                    >


                        <input
                            type="hidden"
                            name="postId"
                            value="<?php echo (int)$selectedPost["id"]; ?>"
                        >


                        <div class="detailsGrid">


                            <div class="formGroup">

                                <label>Post ID</label>

                                <input
                                    type="text"
                                    value="<?php echo (int)$selectedPost["id"]; ?>"
                                    readonly
                                >

                            </div>


                            <div class="formGroup">

                                <label>Post Type</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($selectedPost["post_type"]); ?>"
                                    readonly
                                >

                            </div>


                            <div class="formGroup fullWidth">

                                <label>Title</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($selectedPost["title"]); ?>"
                                    readonly
                                >

                            </div>


                            <div class="formGroup">

                                <label>Author</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($selectedAuthor); ?>"
                                    readonly
                                >

                            </div>


                            <div class="formGroup">

                                <label>Created</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($selectedCreatedAt); ?>"
                                    readonly
                                >

                            </div>


                            <div class="formGroup fullWidth">

                                <label>Body</label>

                                <textarea readonly><?php echo htmlspecialchars($selectedPost["description"]); ?></textarea>

                            </div>


                        </div>


                        <div class="mediaGrid">


                            <div class="mediaBox">

                                <h4>Image</h4>


                                <?php if (!empty($selectedPost["photo_path"])) { ?>

                                    <div class="imagePlaceholder">

                                        <a
                                            href="../<?php echo htmlspecialchars($selectedPost["photo_path"]); ?>"
                                            target="_blank"
                                        >
                                            View Image
                                        </a>

                                    </div>


                                    <p>

                                        <?php

                                        echo htmlspecialchars(
                                            basename(
                                                $selectedPost["photo_path"]
                                            )
                                        );

                                        ?>

                                    </p>


                                <?php } else { ?>

                                    <div class="noMedia">
                                        No image attached
                                    </div>

                                <?php } ?>


                            </div>


                            <div class="mediaBox">

                                <h4>Video</h4>


                                <?php if (!empty($selectedPost["video_path"])) { ?>

                                    <div class="videoPlaceholder">
                                        Video Attached
                                    </div>


                                    <a
                                        href="../<?php echo htmlspecialchars($selectedPost["video_path"]); ?>"
                                        target="_blank"
                                    >

                                        <button
                                            type="button"
                                            class="playButton"
                                        >
                                            Play Video
                                        </button>

                                    </a>


                                <?php } else { ?>

                                    <div class="noMedia">
                                        No video attached
                                    </div>

                                <?php } ?>


                            </div>


                        </div>


                        <div class="decisionButtons">


                            <button
                                type="submit"
                                name="decision"
                                value="Approve"
                                class="approveButton"
                            >
                                Approve
                            </button>


                            <button
                                type="submit"
                                name="decision"
                                value="Reject"
                                class="rejectButton"
                            >
                                Reject
                            </button>


                            <a
                                href="PostApproval.php"
                                class="clearButton"
                            >
                                Clear
                            </a>


                        </div>


                    </form>


                <?php } ?>


            </section>


        </div>


    </main>


</div>


</body>

</html>