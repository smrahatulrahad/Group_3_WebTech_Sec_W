<?php
include "../Controller/PostApproval.php";
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
        href="AdminNewsfeed.php"
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

            <a href="AdminNewsfeed.php">
                Newsfeed
            </a>

            <a href="ShowCases.php">
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

            <a href="UserManagement.php">
                User Management
            </a>

        </div>


        <a
            href="../Controller/logout.php"
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