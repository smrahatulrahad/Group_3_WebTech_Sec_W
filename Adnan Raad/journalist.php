<?php
session_start();

$journalistName = "Journalist";

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
?>

<html>

<head>
    <title>CivicLens - Journalist Feed</title>
    <link rel="stylesheet" href="journalist.css">
</head>

<body>

<div class="header">

    <div class="header-title">
        Journalist Feed
    </div>

    <div class="search-area">

        <input type="text"
               id="searchInput"
               placeholder="Search posts...">

        <button onclick="searchPosts()">
            Search
        </button>

        <select id="filterSelect" onchange="filterPosts()">

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

        <button onclick="refreshPage()">
            Refresh
        </button>

    </div>


    <div class="header-right">

        <span class="journalist-name">
            <?php echo $journalistName; ?>
        </span>

        <a href="logout.php" class="logout-btn">
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

            <a href="Profile.php">
                Profile
            </a>

            <a href="ShowCases.php">
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
                0 posts
            </span>

        </div>


        <div id="postContainer">


            <?php foreach ($posts as $post) { ?>


                <?php
                $emergencyClass = "";

                if ($post["emergency"] == true) {
                    $emergencyClass = "emergency-card";
                }
                ?>


                <div class="post-card <?php echo $emergencyClass; ?>"
                     data-covered="<?php echo $post["covered"] ? "true" : "false"; ?>"
                     data-emergency="<?php echo $post["emergency"] ? "true" : "false"; ?>">


                    <div class="post-header">


                        <div>

                            <h3>
                                <?php echo $post["title"]; ?>
                            </h3>

                            <p class="owner">

                                Posted by

                                <strong>
                                    <?php echo $post["owner"]; ?>
                                </strong>

                                •

                                <?php echo $post["date"]; ?>

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
                        <?php echo $post["content"]; ?>
                    </p>


                    <div class="post-actions">


                        <div>

                            <?php if ($post["video"] == true) { ?>

                                <button class="video-btn"
                                        onclick="playVideo()">

                                    ▶ Play Video

                                </button>

                            <?php } ?>

                        </div>


                        <div>

                            <?php if ($post["covered"] == true) { ?>

                                <button class="cover-btn covered-button"
                                        onclick="toggleCoverage(this)">

                                    Uncover

                                </button>

                            <?php } else { ?>

                                <button class="cover-btn"
                                        onclick="toggleCoverage(this)">

                                    Cover This Post

                                </button>

                            <?php } ?>

                        </div>


                    </div>


                </div>


            <?php } ?>


        </div>


        <div id="noResult" class="no-result">
            No posts found.
        </div>


    </div>

</div>


<script src="journalist.js"></script>

</body>

</html>