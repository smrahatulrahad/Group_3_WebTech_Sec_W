<?php
include "../Controller/UserNewsfeed.php";
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        CivicLens - User Newsfeed
    </title>

    <!-- <link
        rel="stylesheet"
        href="CSS/UserNewsfeed.css"
    > -->
    <link rel="stylesheet" href="CSS/RahatulStyle.css">

</head>


<body class="newsfeedPage">


<header class="header">


    <div class="brand">

        <h1>CivicLens</h1>

        <p>
            Community Newsfeed
        </p>

    </div>



    <form
        class="searchBox"
        action="UserNewsfeed.php"
        method="get"
    >

        <input
            type="text"
            name="search"
            placeholder="Search posts..."
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">
            Search
        </button>

    </form>



    <div class="headerButtons">

        <a
            href="CreatePost.php"
            class="createPost"
        >
            + Post
        </a>

        <a
            href="UserNewsfeed.php"
            class="refreshButton"
        >
            Refresh
        </a>

    </div>


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


            <a
                href="UserNewsfeed.php"
                class="active"
            >
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
            href="../Controller/logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="feedHeader">


            <div>

                <h2>
                    Community Newsfeed
                </h2>

                <p>
                    Approved civic reports and community updates.
                </p>

            </div>


            <span class="postCount">

                <?php

                echo $postCount;

                echo $postCount == 1
                    ? " Post"
                    : " Posts";

                ?>

            </span>


        </div>



        <?php if ($search != "") { ?>

            <div class="searchMessage">

                Search results for:

                <strong>

                    <?php
                    echo htmlspecialchars($search);
                    ?>

                </strong>

                <a href="UserNewsfeed.php">
                    Clear Search
                </a>

            </div>

        <?php } ?>



        <?php if ($postCount == 0) { ?>


            <div class="noPosts">

                <h3>
                    No Posts Found
                </h3>

                <p>

                    <?php if ($search != "") { ?>

                        No approved posts match your search.

                    <?php } else { ?>

                        There are currently no approved posts.

                    <?php } ?>

                </p>

            </div>


        <?php } ?>



        <?php foreach ($posts as $post) { ?>


            <?php

            $isEmergency =
                $post["post_type"] == "Emergency Post";


            if ($post["anonymous"] == 1) {

                $authorName = "Anonymous";

            }

            else {

                $authorName =
                    $post["fullname"];

            }


            $createdDate = date(
                "d M Y, h:i A",
                strtotime(
                    $post["created_at"]
                )
            );


            $hasPhoto =
                !empty($post["photo_path"]);


            $hasVideo =
                !empty($post["video_path"]);

            ?>


            <div
                class="postCard<?php echo $isEmergency ? " emergencyPost" : ""; ?>"
            >


                <div class="postHeading">


                    <div>


                        <h3>

                            <?php

                            echo htmlspecialchars(
                                $post["title"]
                            );

                            ?>

                        </h3>


                        <p class="postInformation">

                            By

                            <?php

                            echo htmlspecialchars(
                                $authorName
                            );

                            ?>

                            <span>•</span>

                            <?php

                            echo htmlspecialchars(
                                $createdDate
                            );

                            ?>

                        </p>


                    </div>



                    <?php if ($isEmergency) { ?>

                        <span class="emergencyBadge">
                            EMERGENCY
                        </span>

                    <?php } ?>


                </div>



                <span class="activity">

                    <?php

                    if ($isEmergency) {

                        echo "Emergency post approved";

                    }

                    else {

                        echo "Post approved";

                    }

                    ?>

                </span>



                <div class="postContent">


                    <p>

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $post["description"]
                            )
                        );

                        ?>

                    </p>



                    <?php if ($hasPhoto || $hasVideo) { ?>


                        <div class="mediaBox">


                            <?php if ($hasPhoto) { ?>

                                <span>
                                    Image attached
                                </span>

                                <a
                                    href="../<?php echo htmlspecialchars($post["photo_path"]); ?>"
                                    target="_blank"
                                >

                                    <button type="button">
                                        View Image
                                    </button>

                                </a>

                            <?php } ?>



                            <?php if ($hasVideo) { ?>

                                <span>
                                    Video attached
                                </span>

                                <a
                                    href="../<?php echo htmlspecialchars($post["video_path"]); ?>"
                                    target="_blank"
                                >

                                    <button type="button">
                                        Play Video
                                    </button>

                                </a>

                            <?php } ?>


                        </div>


                    <?php } ?>


                </div>


            </div>


        <?php } ?>


    </main>


</div>


</body>

</html>