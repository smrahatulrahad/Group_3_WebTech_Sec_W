<?php
session_start();


if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: login.php");
    exit();
}


if (
    $_SESSION["userRole"] != "Admin" &&
    $_SESSION["userRole"] != "Moderator"
) {
    header("Location: login.php");
    exit();
}


$userName = $_SESSION["userName"];
$userRole = $_SESSION["userRole"];

$selectedPost = $_GET["post"] ?? "";
$message = "";

$postId = "";
$title = "";
$author = "";
$createdAt = "";
$postType = "";
$body = "";
$imageName = "";
$hasVideo = false;


if ($selectedPost == "1") {

    $postId = "201";
    $title = "Broken Drain Cover Near School";
    $author = "Rahim Ahmed";
    $createdAt = "20 Aug 2026, 06:30 PM";
    $postType = "Normal Post";
    $body = "A drain cover near the school gate is broken and may cause accidents. Please repair it as soon as possible.";
    $imageName = "broken_drain.jpg";
    $hasVideo = false;

}


if ($selectedPost == "2") {

    $postId = "202";
    $title = "Waterlogging After Heavy Rain";
    $author = "Nabila Islam";
    $createdAt = "20 Aug 2026, 04:10 PM";
    $postType = "Emergency Post";
    $body = "Heavy rain has caused severe waterlogging in the residential road. People are having difficulty using the road.";
    $imageName = "waterlogging.jpg";
    $hasVideo = true;

}


if ($selectedPost == "3") {

    $postId = "203";
    $title = "Street Light Not Working";
    $author = "Tanvir Hasan";
    $createdAt = "19 Aug 2026, 10:05 PM";
    $postType = "Normal Post";
    $body = "Several street lights beside the community park have not been working for the last few days.";
    $imageName = "";
    $hasVideo = false;

}


if (!isset($_SESSION["postStatus"])) {
    $_SESSION["postStatus"] = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $decision = $_POST["decision"] ?? "";

    if (
        $selectedPost != "" &&
        ($decision == "Approve" || $decision == "Reject")
    ) {

        $_SESSION["postStatus"][$selectedPost] = $decision;

        if ($decision == "Approve") {
            $message = "Post approved successfully.";
        }

        if ($decision == "Reject") {
            $message = "Post rejected successfully.";
        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Post Approval</title>

    <link rel="stylesheet" href="CSS/PostApproval.css">

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Post Approval</p>

    </div>


    <a href="../Adnan Raad/AdminNewsfeed.php" class="backTop">
        Back to Admin Newsfeed
    </a>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">


            <div class="avatar">

                <?php echo strtoupper(substr($userName, 0, 1)); ?>

            </div>


            <div>

                <small>Signed in as</small>

                <strong>
                    <?php echo htmlspecialchars($userName); ?>
                </strong>

                <span>
                    <?php echo htmlspecialchars($userRole); ?>
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


            <a href="PostApproval.php" class="active">
                Post Approval
            </a>


            <a href="StaffManagement.php">
                Staff Management
            </a>


            <a href="../Adnan Raad/UserManagement.php">
                User Management
            </a>


        </div>


        <a href="logout.php" class="logout">
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

                <?php echo htmlspecialchars($message); ?>

            </div>


        <?php } ?>



        <div class="topTools">


            <div class="searchBox">

                <input
                    type="text"
                    placeholder="Search pending posts..."
                >

                <button type="button">
                    Search
                </button>

            </div>


            <a href="PostApproval.php" class="refreshButton">
                Refresh
            </a>


        </div>



        <div class="contentGrid">


            <section class="pendingSection">


                <div class="sectionTitle">

                    <h3>Pending Posts</h3>

                    <span>3 Posts</span>

                </div>



                <div class="postItem">


                    <div>

                        <h4>
                            Broken Drain Cover Near School
                        </h4>

                        <p>
                            Rahim Ahmed
                        </p>

                        <small>
                            20 Aug 2026, 06:30 PM
                        </small>

                    </div>


                    <a href="PostApproval.php?post=1">
                        Review
                    </a>


                </div>



                <div class="postItem emergencyItem">


                    <div>

                        <h4>
                            Waterlogging After Heavy Rain
                        </h4>

                        <p>
                            Nabila Islam
                        </p>

                        <small>
                            20 Aug 2026, 04:10 PM
                        </small>

                    </div>


                    <a href="PostApproval.php?post=2">
                        Review
                    </a>


                </div>



                <div class="postItem">


                    <div>

                        <h4>
                            Street Light Not Working
                        </h4>

                        <p>
                            Tanvir Hasan
                        </p>

                        <small>
                            19 Aug 2026, 10:05 PM
                        </small>

                    </div>


                    <a href="PostApproval.php?post=3">
                        Review
                    </a>


                </div>


            </section>



            <section class="detailsSection">


                <div class="sectionTitle">

                    <h3>Post Details</h3>

                </div>



                <?php if ($selectedPost == "") { ?>


                    <div class="emptyDetails">

                        <h4>Select a pending post</h4>

                        <p>
                            Click Review to see the full post before approving or rejecting it.
                        </p>

                    </div>


                <?php } ?>



                <?php if ($selectedPost != "") { ?>


                    <form action="PostApproval.php?post=<?php echo htmlspecialchars($selectedPost); ?>" method="post">


                        <div class="detailsGrid">


                            <div class="formGroup">

                                <label>Post ID</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($postId); ?>"
                                    readonly
                                >

                            </div>



                            <div class="formGroup">

                                <label>Post Type</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($postType); ?>"
                                    readonly
                                >

                            </div>



                            <div class="formGroup fullWidth">

                                <label>Title</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($title); ?>"
                                    readonly
                                >

                            </div>



                            <div class="formGroup">

                                <label>Author</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($author); ?>"
                                    readonly
                                >

                            </div>



                            <div class="formGroup">

                                <label>Created</label>

                                <input
                                    type="text"
                                    value="<?php echo htmlspecialchars($createdAt); ?>"
                                    readonly
                                >

                            </div>



                            <div class="formGroup fullWidth">

                                <label>Body</label>

                                <textarea readonly><?php echo htmlspecialchars($body); ?></textarea>

                            </div>


                        </div>



                        <div class="mediaGrid">


                            <div class="mediaBox">

                                <h4>Image</h4>


                                <?php if ($imageName != "") { ?>


                                    <div class="imagePlaceholder">
                                        Image Preview
                                    </div>

                                    <p>
                                        <?php echo htmlspecialchars($imageName); ?>
                                    </p>


                                <?php } ?>


                                <?php if ($imageName == "") { ?>


                                    <div class="noMedia">
                                        No image attached
                                    </div>


                                <?php } ?>


                            </div>



                            <div class="mediaBox">

                                <h4>Video</h4>


                                <?php if ($hasVideo == true) { ?>


                                    <div class="videoPlaceholder">
                                        Video Attached
                                    </div>


                                    <button type="button" class="playButton">
                                        Play Video
                                    </button>


                                <?php } ?>


                                <?php if ($hasVideo == false) { ?>


                                    <div class="noMedia">
                                        No video attached
                                    </div>


                                <?php } ?>


                            </div>


                        </div>



                        <div class="noteBox">

                            <label>Moderator Note</label>

                            <input
                                type="text"
                                name="moderatorNote"
                                placeholder="Optional note for this decision"
                            >

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


                            <a href="PostApproval.php" class="clearButton">
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