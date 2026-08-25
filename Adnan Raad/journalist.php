<?php
session_start();


if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


if ($_SESSION["userRole"] != "Journalist") {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$journalistName = $_SESSION["userName"];

$posts = [
    [
        "id" => 1,
        "title" => "Road Accident Near Airport",
        "owner" => "Rahim Ahmed",
        "date" => "20 Aug 2026",
        "content" => "A serious road accident occurred near the airport road. Authorities have already been informed.",
        "emergency" => true,
        "covered" => false,
        "video" => true
    ],
    [
        "id" => 2,
        "title" => "Broken Street Lights",
        "owner" => "Karim Hasan",
        "date" => "19 Aug 2026",
        "content" => "Several street lights are not working in the residential area and residents are requesting action.",
        "emergency" => false,
        "covered" => true,
        "video" => false
    ],
    [
        "id" => 3,
        "title" => "Garbage Collection Problem",
        "owner" => "Nadia Islam",
        "date" => "18 Aug 2026",
        "content" => "Garbage has not been collected from the area for several days and is causing problems for residents.",
        "emergency" => false,
        "covered" => false,
        "video" => false
    ],
    [
        "id" => 4,
        "title" => "Fire Reported in Local Market",
        "owner" => "Arif Hossain",
        "date" => "17 Aug 2026",
        "content" => "A fire has been reported in a local market. Fire service and emergency responders are present.",
        "emergency" => true,
        "covered" => true,
        "video" => true
    ]
];


/* Store covered posts in SESSION */
if (!isset($_SESSION["journalistCoveredPosts"])) {

    $_SESSION["journalistCoveredPosts"] = [];

    foreach ($posts as $post) {

        if ($post["covered"] == true) {
            $_SESSION["journalistCoveredPosts"][] = $post["id"];
        }

    }

}


/* Cover / Uncover post */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = $_POST["postId"] ?? "";

    if ($postId != "") {

        $postId = (int) $postId;

        if (in_array($postId, $_SESSION["journalistCoveredPosts"])) {

            $_SESSION["journalistCoveredPosts"] =
                array_values(
                    array_diff(
                        $_SESSION["journalistCoveredPosts"],
                        [$postId]
                    )
                );

        } else {

            $_SESSION["journalistCoveredPosts"][] = $postId;

        }

    }

    header("Location: journalist.php");
    exit();
}


/* Update covered state */
foreach ($posts as $key => $post) {

    $posts[$key]["covered"] =
        in_array(
            $post["id"],
            $_SESSION["journalistCoveredPosts"]
        );

}


/* Search and Filter */
$searchText = trim($_GET["search"] ?? "");
$filter = $_GET["filter"] ?? "all";

if (isset($_GET["reset"])) {
    $searchText = "";
    $filter = "all";
}


$filteredPosts = [];

foreach ($posts as $post) {

    $showPost = true;


    if ($searchText != "") {

        if (
            stripos($post["title"], $searchText) === false &&
            stripos($post["content"], $searchText) === false &&
            stripos($post["owner"], $searchText) === false
        ) {

            $showPost = false;

        }

    }


    if ($filter == "covered" && $post["covered"] == false) {
        $showPost = false;
    }


    if ($filter == "uncovered" && $post["covered"] == true) {
        $showPost = false;
    }


    if ($showPost == true) {
        $filteredPosts[] = $post;
    }

}
?>

<html>

<head>
    <title>CivicLens - Journalist Feed</title>
    <link rel="stylesheet" href="CSS/journalist.css">
</head>

<body>

<div class="header">

    <div class="header-title">
        Journalist Feed
    </div>


    <form class="search-area" action="journalist.php" method="get">

        <input
            type="text"
            name="search"
            placeholder="Search posts..."
            value="<?php echo htmlspecialchars($searchText); ?>"
        >


        <button type="submit">
            Search
        </button>


        <select name="filter">

            <option
                value="all"
                <?php
                if ($filter == "all") {
                    echo "selected";
                }
                ?>
            >
                All Posts
            </option>


            <option
                value="covered"
                <?php
                if ($filter == "covered") {
                    echo "selected";
                }
                ?>
            >
                Covered by Me
            </option>


            <option
                value="uncovered"
                <?php
                if ($filter == "uncovered") {
                    echo "selected";
                }
                ?>
            >
                Uncovered
            </option>

        </select>


        <button
            type="submit"
            name="reset"
            value="1"
        >
            Refresh
        </button>

    </form>



    <div class="header-right">

        <span class="journalist-name">
            <?php echo htmlspecialchars($journalistName); ?>
        </span>

        <a href="../utsa_Mazumdar/logout.php" class="logout-btn">
            Logout
        </a>

    </div>

</div>



<div class="page-container">


    <div class="sidebar">

        <div class="sidebar-menu">

            <a href="journalist.php" class="active">
                News Feed
            </a>

            <a href="../S.M. Rahatul Islam/Profile.php">
                Profile
            </a>

            <a href="../S.M. Rahatul Islam/ShowCases.php">
                Show Cases
            </a>

        </div>

    </div>



    <div class="main-content">


        <div class="welcome-section">

            <div>

                <h2>
                    Journalist News Feed
                </h2>

                <p>
                    View approved citizen posts and select issues to cover.
                </p>

            </div>

        </div>



        <div class="result-bar">

            <span>
                Approved Posts
            </span>

            <span id="postCount">
                <?php echo count($filteredPosts); ?> posts
            </span>

        </div>



        <div id="postContainer">


            <?php foreach ($filteredPosts as $post) { ?>


                <?php

                $emergencyClass = "";

                if ($post["emergency"] == true) {
                    $emergencyClass = "emergency-card";
                }

                ?>


                <div
                    class="post-card <?php echo $emergencyClass; ?>"
                    data-covered="<?php echo $post["covered"] ? "true" : "false"; ?>"
                    data-emergency="<?php echo $post["emergency"] ? "true" : "false"; ?>"
                >


                    <div class="post-header">


                        <div>

                            <h3>
                                <?php echo htmlspecialchars($post["title"]); ?>
                            </h3>


                            <p class="owner">

                                Posted by

                                <strong>
                                    <?php echo htmlspecialchars($post["owner"]); ?>
                                </strong>

                                •

                                <?php echo htmlspecialchars($post["date"]); ?>

                            </p>

                        </div>



                        <div class="tag-area">


                            <?php if ($post["covered"] == true) { ?>

                                <span class="covered-tag">
                                    Covered by You
                                </span>

                            <?php } ?>


                            <?php if ($post["emergency"] == true) { ?>

                                <span class="emergency-tag">
                                    Emergency
                                </span>

                            <?php } ?>


                        </div>

                    </div>



                    <p class="post-content">
                        <?php echo htmlspecialchars($post["content"]); ?>
                    </p>



                    <div class="post-actions">


                        <div>

                            <?php if ($post["video"] == true) { ?>

                                <button
                                    type="button"
                                    class="video-btn"
                                >
                                    ▶ Play Video
                                </button>

                            <?php } ?>

                        </div>



                        <div>

                            <form action="journalist.php" method="post">

                                <input
                                    type="hidden"
                                    name="postId"
                                    value="<?php echo $post["id"]; ?>"
                                >


                                <?php if ($post["covered"] == true) { ?>

                                    <button
                                        type="submit"
                                        class="cover-btn covered-button"
                                    >
                                        Uncover
                                    </button>

                                <?php } else { ?>

                                    <button
                                        type="submit"
                                        class="cover-btn"
                                    >
                                        Cover This Post
                                    </button>

                                <?php } ?>


                            </form>

                        </div>


                    </div>


                </div>


            <?php } ?>


        </div>



        <?php if (count($filteredPosts) == 0) { ?>

            <div
                id="noResult"
                class="no-result"
                style="display: block;"
            >
                No posts found.
            </div>

        <?php } ?>


    </div>

</div>


</body>

</html>