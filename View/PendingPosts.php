<?php
include "../Controller/PendingPosts.php";
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
        CivicLens - Pending Posts
    </title>

    <!-- <link
        rel="stylesheet"
        href="CSS/PendingPosts.css"
    > -->

    <link rel="stylesheet" href="CSS/RahatulStyle.css">

</head>


<body class="pendingPostsPage">


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>
            Pending Posts
        </p>

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


            <a
                href="PendingPosts.php"
                class="active"
            >
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


        <div class="pageTitle">


            <h2>
                My Pending Posts
            </h2>


            <p>
                View and edit posts that are waiting for approval.
            </p>



            <?php if ($successMessage != "") { ?>

                <p>

                    <?php

                    echo htmlspecialchars(
                        $successMessage
                    );

                    ?>

                </p>

            <?php } ?>



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



        <div class="contentGrid">


            <section class="pendingList">


                <h3>
                    Pending Posts
                </h3>



                <?php if (count($pendingPosts) == 0) { ?>


                    <div class="emptyEditor">

                        <h4>
                            No Pending Posts
                        </h4>

                        <p>
                            You currently have no posts waiting
                            for approval.
                        </p>

                    </div>


                <?php } ?>



                <?php foreach ($pendingPosts as $post) { ?>


                    <?php


                    $isEmergency =
                        $post["post_type"] ==
                        "Emergency Post";


                    $createdAt = date(
                        "d M Y, h:i A",
                        strtotime(
                            $post["created_at"]
                        )
                    );


                    $shortText =
                        $post["description"];


                    if (strlen($shortText) > 120) {

                        $shortText =
                            substr(
                                $shortText,
                                0,
                                120
                            ) . "...";

                    }


                    ?>


                    <div
                        class="postItem<?php echo $isEmergency ? " emergencyItem" : ""; ?>"
                    >


                        <div class="postTop">


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
                                        $createdAt
                                    );

                                    ?>

                                </p>


                            </div>



                            <?php if ($isEmergency) { ?>


                                <span class="emergencyBadge">
                                    Emergency
                                </span>


                            <?php } else { ?>


                                <span class="normalBadge">
                                    Normal
                                </span>


                            <?php } ?>


                        </div>



                        <p class="shortText">

                            <?php

                            echo htmlspecialchars(
                                $shortText
                            );

                            ?>

                        </p>



                        <a
                            href="PendingPosts.php?post=<?php echo (int) $post["id"]; ?>"
                            class="editButton"
                        >
                            Edit Post
                        </a>


                    </div>


                <?php } ?>


            </section>



            <section class="editSection">


                <h3>
                    Edit Post
                </h3>



                <?php if ($selectedPost === null) { ?>


                    <div class="emptyEditor">


                        <h4>
                            Select a pending post
                        </h4>


                        <p>
                            Click the Edit Post button from
                            the left side to view and edit a post.
                        </p>


                    </div>


                <?php } ?>



                <?php if ($selectedPost !== null) { ?>


                    <?php


                    $selectedCreatedAt = date(
                        "d M Y, h:i A",
                        strtotime(
                            $selectedPost["created_at"]
                        )
                    );


                    ?>


                    <form
                        action="PendingPosts.php"
                        method="post"
                    >


                        <label>
                            Post ID
                        </label>


                        <input
                            type="text"
                            name="postId"
                            value="<?php echo (int) $selectedPost["id"]; ?>"
                            readonly
                        >



                        <label>
                            Title
                        </label>


                        <input
                            type="text"
                            name="title"
                            value="<?php echo htmlspecialchars($selectedPost["title"]); ?>"
                            required
                        >



                        <label>
                            Category
                        </label>


                        <select
                            name="category"
                            required
                        >


                            <option
                                value="Normal Post"

                                <?php

                                if (
                                    $selectedPost["post_type"] ==
                                    "Normal Post"
                                ) {

                                    echo "selected";

                                }

                                ?>
                            >
                                Normal Post
                            </option>



                            <option
                                value="Emergency Post"

                                <?php

                                if (
                                    $selectedPost["post_type"] ==
                                    "Emergency Post"
                                ) {

                                    echo "selected";

                                }

                                ?>
                            >
                                Emergency Post
                            </option>


                        </select>



                        <label>
                            Body
                        </label>


                        <textarea
                            name="body"
                            required
                        ><?php echo htmlspecialchars($selectedPost["description"]); ?></textarea>



                        <label>
                            Created At
                        </label>


                        <input
                            type="text"
                            value="<?php echo htmlspecialchars($selectedCreatedAt); ?>"
                            readonly
                        >



                        <div class="formButtons">


                            <button
                                type="submit"
                                class="saveButton"
                            >
                                Save Changes
                            </button>


                            <a
                                href="PendingPosts.php"
                                class="discardButton"
                            >
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