<?php
include "../Controller/journalist.php";
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
        CivicLens - Journalist Feed
    </title>


    <link
        rel="stylesheet"
        href="CSS/journalist.css"
    >

</head>


<body>


<!-- =====================================
     HEADER
===================================== -->

<header class="header">


    <div class="header-title">

        Journalist Feed

    </div>



    <!-- SEARCH AREA -->

    <div class="search-area">


        <input
            type="text"
            id="searchInput"
            placeholder="Search posts..."
        >


        <button
            type="button"
            onclick="searchPosts()"
        >
            Search
        </button>



        <select
            id="filterSelect"
            onchange="filterPosts()"
        >

            <option value="all">
                All Posts
            </option>

            <option value="covered">
                Covered by Me
            </option>

            <option value="uncovered">
                Uncovered
            </option>

        </select>



        <button
            type="button"
            onclick="refreshPage()"
        >
            Refresh
        </button>


    </div>



    <!-- RIGHT SIDE -->

    <div class="header-right">


        <span class="journalist-name">

            <?php

            echo htmlspecialchars(
                $journalistName
            );

            ?>

        </span>



        <a
            href="../Controller/logout.php"
            class="logout-btn"
        >
            Logout
        </a>


    </div>


</header>



<!-- =====================================
     PAGE
===================================== -->

<div class="page-container">


    <!-- =====================================
         LEFT SIDEBAR
    ===================================== -->

    <aside class="sidebar">


        <div class="sidebar-menu">


            <a
                href="journalist.php"
                class="active"
            >
                News Feed
            </a>

            <a href="Profile.php">
              Profile
            </a>


            <a
                href="ShowCases.php"
            >
                Show Cases
            </a>


        </div>


    </aside>



    <!-- =====================================
         MAIN CONTENT
    ===================================== -->

    <main class="main-content">


        <!-- WELCOME -->

        <section class="welcome-section">


            <div>

                <h2>
                    Journalist News Feed
                </h2>


                <p>
                    View approved citizen posts and select issues to cover.
                </p>

            </div>



            <a
                href="ShowCases.php"
                class="show-cases-btn"
            >
                Show Cases
            </a>


        </section>



        <!-- RESULT BAR -->

        <div class="result-bar">


            <span>
                Approved Posts
            </span>


            <span id="postCount">
                0 posts
            </span>


        </div>



        <!-- =====================================
             POSTS
        ===================================== -->

        <div id="postContainer">


            <?php

            $approvedPostFound = false;


            if (
                $postsResult &&
                $postsResult->num_rows > 0
            ) {


                while (
                    $post =
                    $postsResult->fetch_assoc()
                ) {


                    /* Only Approved posts */

                    if (
                        $post["status"] != "Approved"
                    ) {
                        continue;
                    }


                    $approvedPostFound = true;



                    /* =========================
                       POST INFORMATION
                    ========================= */

                    $postInfo =
                        $journalistPostInfo[
                            (int)$post["id"]
                        ] ?? array();


                    $ownerName =
                        $postInfo["ownerName"]
                        ?? "Anonymous User";


                    $covered =
                        $postInfo["covered"]
                        ?? false;


                    /* =========================
                       EMERGENCY
                    ========================= */

                    $emergency = false;


                    if (
                        $post["post_type"]
                        == "Emergency Post"
                    ) {
                        $emergency = true;
                    }



                    /* =========================
                       VIDEO
                    ========================= */

                    $hasVideo = false;


                    if (
                        isset($post["video_path"]) &&
                        trim($post["video_path"]) != ""
                    ) {
                        $hasVideo = true;
                    }

            ?>


                <article
                    class="post-card<?php
                        if ($emergency) {
                            echo " emergency-card";
                        }
                    ?>"

                    data-covered="<?php
                        echo $covered
                            ? "true"
                            : "false";
                    ?>"

                    data-emergency="<?php
                        echo $emergency
                            ? "true"
                            : "false";
                    ?>"
                >


                    <!-- POST HEADER -->

                    <div class="post-header">


                        <div>


                            <h3>

                                <?php

                                echo htmlspecialchars(
                                    $post["title"]
                                );

                                ?>

                            </h3>



                            <p class="owner">

                                Posted by

                                <strong>

                                    <?php

                                    echo htmlspecialchars(
                                        $ownerName
                                    );

                                    ?>

                                </strong>

                                •

                                <?php

                                echo htmlspecialchars(
                                    date(
                                        "d M Y",
                                        strtotime(
                                            $post["created_at"]
                                        )
                                    )
                                );

                                ?>

                            </p>


                        </div>



                        <!-- TAGS -->

                        <div class="tag-area">


                            <?php if ($covered) { ?>


                                <span class="covered-tag">

                                    Covered by You

                                </span>


                            <?php } ?>



                            <?php if ($emergency) { ?>


                                <span class="emergency-tag">

                                    Emergency

                                </span>


                            <?php } ?>


                        </div>


                    </div>



                    <!-- POST CONTENT -->

                    <p class="post-content">

                        <?php

                        echo nl2br(
                            htmlspecialchars(
                                $post["description"]
                            )
                        );

                        ?>

                    </p>



                    <!-- POST ACTIONS -->

                    <div class="post-actions">


                        <div>


                            <?php if ($hasVideo) { ?>


                                <a
                                    href="../<?php
                                        echo htmlspecialchars(
                                            $post["video_path"]
                                        );
                                    ?>"
                                    class="video-btn"
                                    target="_blank"
                                >
                                    ▶ Play Video
                                </a>


                            <?php } ?>


                        </div>



                        <div>


                            <?php if ($covered) { ?>


                                <form
                                    action="journalist.php"
                                    method="post"
                                >


                                    <input
                                        type="hidden"
                                        name="post_id"
                                        value="<?php
                                            echo (int)$post["id"];
                                        ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="uncover"
                                    >


                                    <button
                                        type="submit"
                                        class="cover-btn covered-button"
                                    >
                                        Uncover
                                    </button>


                                </form>



                            <?php } else { ?>


                                <form
                                    action="journalist.php"
                                    method="post"
                                >


                                    <input
                                        type="hidden"
                                        name="post_id"
                                        value="<?php
                                            echo (int)$post["id"];
                                        ?>"
                                    >


                                    <input
                                        type="hidden"
                                        name="action"
                                        value="cover"
                                    >


                                    <button
                                        type="submit"
                                        class="cover-btn"
                                    >
                                        Cover This Post
                                    </button>


                                </form>


                            <?php } ?>


                        </div>


                    </div>


                </article>


            <?php

                }

            }

            ?>


        </div>



        <!-- NO RESULT -->

        <div
            id="noResult"
            class="no-result"
        >

            <?php

            if (!$approvedPostFound) {
                echo "No approved posts found.";
            }
            else {
                echo "No posts found.";
            }

            ?>

        </div>


    </main>


</div>



<script src="JS/journalist.js"></script>


<?php


?>


</body>

</html>