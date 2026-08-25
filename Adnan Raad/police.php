<?php
session_start();

$policeName = "Police Officer";

$posts = [
    [
        "id" => 1,
        "title" => "Road Accident Near Airport",
        "citizen" => "Rahim Ahmed",
        "date" => "20 Aug 2026",
        "content" => "A road accident occurred near the airport road. Immediate police assistance is required.",
        "emergency" => true,
        "status" => "Open",
        "taken_by" => ""
    ],
    [
        "id" => 2,
        "title" => "Illegal Parking Problem",
        "citizen" => "Karim Hasan",
        "date" => "19 Aug 2026",
        "content" => "Several vehicles are illegally parked and blocking the road.",
        "emergency" => false,
        "status" => "In Progress",
        "taken_by" => "me"
    ],
    [
        "id" => 3,
        "title" => "Suspicious Activity",
        "citizen" => "Nadia Islam",
        "date" => "18 Aug 2026",
        "content" => "Suspicious activity has been reported near the residential area.",
        "emergency" => true,
        "status" => "In Progress",
        "taken_by" => "other"
    ],
    [
        "id" => 4,
        "title" => "Traffic Complaint",
        "citizen" => "Arif Hossain",
        "date" => "17 Aug 2026",
        "content" => "Heavy traffic and unauthorized roadside parking have been reported.",
        "emergency" => false,
        "status" => "Resolved",
        "taken_by" => "me"
    ]
];
?>

<html>

<head>
    <title>CivicLens - Police Newsfeed</title>
    <link rel="stylesheet" href="CSS/police.css">
</head>

<body>

<div class="header">

    <div class="header-title">
        Police Newsfeed
    </div>

    <div class="search-area">

        <input type="text"
               id="searchInput"
               placeholder="Search posts...">

        <button onclick="searchPosts()">
            Search
        </button>

        <button onclick="refreshPage()">
            Refresh
        </button>

    </div>

    <div class="header-right">

        <span class="officer-name">
            <?php echo $policeName; ?>
        </span>

        <a href="Profile.php" class="profile-btn">
            Profile
        </a>

        <a href="ShowCases.php" class="show-cases-btn">
            Show Cases
        </a>

        <a href="logout.php" class="logout-btn">
            Logout
        </a>

    </div>

</div>


<div class="main-content">

    <div class="feed-title">

        <div>

            <h2>
                Reported Cases
            </h2>

            <p>
                View citizen complaints and manage assigned cases.
            </p>

        </div>

    </div>


    <div class="filters">

        <button id="allBtn"
                class="active"
                onclick="filterPosts('all')">
            All
        </button>

        <button id="openBtn"
                onclick="filterPosts('Open')">
            Open
        </button>

        <button id="progressBtn"
                onclick="filterPosts('In Progress')">
            In Progress
        </button>

        <button id="resolvedBtn"
                onclick="filterPosts('Resolved')">
            Resolved
        </button>

        <button id="emergencyBtn"
                onclick="filterPosts('Emergency')">
            Emergency
        </button>

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
                 data-status="<?php echo $post["status"]; ?>"
                 data-emergency="<?php echo $post["emergency"] ? "true" : "false"; ?>"
                 data-taken="<?php echo $post["taken_by"]; ?>">

                <div class="post-header">

                    <div>

                        <h3>
                            <?php echo $post["title"]; ?>
                        </h3>

                        <p class="citizen">

                            Citizen:

                            <strong>
                                <?php echo $post["citizen"]; ?>
                            </strong>

                        </p>

                    </div>


                    <div class="right-info">

                        <span class="date">
                            <?php echo $post["date"]; ?>
                        </span>

                        <?php if ($post["emergency"] == true) { ?>

                            <span class="emergency-badge">
                                EMERGENCY
                            </span>

                        <?php } ?>

                    </div>

                </div>


                <p class="content">
                    <?php echo $post["content"]; ?>
                </p>


                <div class="status-container">

                    <?php if ($post["status"] == "Resolved") { ?>

                        <span class="case-status resolved">
                            Resolved
                        </span>

                    <?php } elseif ($post["taken_by"] == "me") { ?>

                        <span class="case-status progress">
                            In Progress (You)
                        </span>

                    <?php } elseif ($post["taken_by"] == "other") { ?>

                        <span class="case-status taken">
                            Taken by another officer
                        </span>

                    <?php } else { ?>

                        <span class="case-status open">
                            Open
                        </span>

                    <?php } ?>

                </div>


                <div class="actions">

                    <?php if ($post["taken_by"] == "me") { ?>

                        <button class="take-btn"
                                onclick="toggleCase(this)">
                            Release Case
                        </button>

                    <?php } else { ?>

                        <button class="take-btn"
                                onclick="toggleCase(this)">
                            Take Case
                        </button>

                    <?php } ?>


                    <?php

                    $disabled = "";

                    if ($post["taken_by"] == "other") {
                        $disabled = "disabled";
                    }

                    ?>


                    <?php if ($post["status"] == "Resolved") { ?>

                        <button class="resolve-btn"
                                onclick="toggleResolved(this)"
                                <?php echo $disabled; ?>>
                            Unmark Resolved
                        </button>

                    <?php } else { ?>

                        <button class="resolve-btn"
                                onclick="toggleResolved(this)"
                                <?php echo $disabled; ?>>
                            Mark Resolved
                        </button>

                    <?php } ?>

                </div>

            </div>

        <?php } ?>

    </div>


    <div id="noResult" class="no-result">
        No cases found.
    </div>

</div>


<div class="footer">

    <span>
        CivicLens Police Portal
    </span>

</div>


<script src="police.js"></script>

</body>

</html>