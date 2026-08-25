<?php
session_start();

$userName = $_SESSION["userName"] ?? "Nafis Rahman";

$selectedPost = $_GET["post"] ?? "";

$postId = "";
$title = "";
$category = "";
$body = "";
$createdAt = "";

if ($selectedPost == "1") {
    $postId = "101";
    $title = "Broken Drain Cover Near School";
    $category = "Normal Post";
    $body = "A drain cover near the school gate is broken and may cause accidents. Please repair it as soon as possible.";
    $createdAt = "20 Aug 2026, 06:30 PM";
}

if ($selectedPost == "2") {
    $postId = "102";
    $title = "Waterlogging After Heavy Rain";
    $category = "Emergency Post";
    $body = "Heavy rain has caused severe waterlogging in the residential road. People are having difficulty using the road.";
    $createdAt = "20 Aug 2026, 04:10 PM";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Pending Posts</title>
    <link rel="stylesheet" href="CSS/PendingPosts.css">
</head>

<body>

<header class="header">

    <div>
        <h1>CivicLens</h1>
        <p>Pending Posts</p>
    </div>

    <a href="UserNewsfeed.php" class="backTop">Back to Newsfeed</a>

</header>


<div class="pageContainer">


    <aside class="sidebar">

        <div class="userInfo">

            <div class="avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>

            <div>
                <small>Signed in as</small>
                <strong><?php echo htmlspecialchars($userName); ?></strong>
            </div>

        </div>


        <div class="menu">

            <a href="UserNewsfeed.php">Newsfeed</a>

            <a href="Profile.php">Profile</a>

            <a href="PendingPosts.php" class="active">Pending Posts</a>

            <a href="ShowCases.php">Show Cases</a>

            <a href="Donation.php">Donation</a>

        </div>


        <a href="../utsa_Mazumdar/View/login.php" class="logout">Logout</a>

    </aside>



    <main class="mainContent">

        <div class="pageTitle">
            <h2>My Pending Posts</h2>
            <p>View and edit posts that are waiting for approval.</p>
        </div>


        <div class="contentGrid">


            <section class="pendingList">

                <h3>Pending Posts</h3>


                <div class="postItem">

                    <div class="postTop">

                        <div>
                            <h4>Broken Drain Cover Near School</h4>
                            <p>20 Aug 2026, 06:30 PM</p>
                        </div>

                        <span class="normalBadge">Normal</span>

                    </div>

                    <p class="shortText">
                        A drain cover near the school gate is broken and may cause accidents.
                    </p>

                    <a href="PendingPosts.php?post=1" class="editButton">
                        Edit Post
                    </a>

                </div>



                <div class="postItem emergencyItem">

                    <div class="postTop">

                        <div>
                            <h4>Waterlogging After Heavy Rain</h4>
                            <p>20 Aug 2026, 04:10 PM</p>
                        </div>

                        <span class="emergencyBadge">Emergency</span>

                    </div>

                    <p class="shortText">
                        Heavy rain has caused severe waterlogging in the residential road.
                    </p>

                    <a href="PendingPosts.php?post=2" class="editButton">
                        Edit Post
                    </a>

                </div>

            </section>



            <section class="editSection">

                <h3>Edit Post</h3>


                <?php if ($selectedPost == "") { ?>

                    <div class="emptyEditor">

                        <h4>Select a pending post</h4>

                        <p>
                            Click the Edit Post button from the left side to view and edit a post.
                        </p>

                    </div>

                <?php } ?>


                <?php if ($selectedPost != "") { ?>

                    <form action="PendingPosts.php" method="post">


                        <label>Post ID</label>

                        <input
                            type="text"
                            name="postId"
                            value="<?php echo htmlspecialchars($postId); ?>"
                            readonly
                        >


                        <label>Title</label>

                        <input
                            type="text"
                            name="title"
                            value="<?php echo htmlspecialchars($title); ?>"
                        >


                        <label>Category</label>

                        <select name="category">

                            <option
                                <?php
                                if ($category == "Normal Post") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Normal Post
                            </option>

                            <option
                                <?php
                                if ($category == "Emergency Post") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Emergency Post
                            </option>

                        </select>


                        <label>Body</label>

                        <textarea name="body"><?php echo htmlspecialchars($body); ?></textarea>


                        <label>Created At</label>

                        <input
                            type="text"
                            value="<?php echo htmlspecialchars($createdAt); ?>"
                            readonly
                        >


                        <div class="formButtons">

                            <button type="submit" class="saveButton">
                                Save Changes
                            </button>

                            <a href="PendingPosts.php" class="discardButton">
                                Discard
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