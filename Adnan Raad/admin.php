<?php
session_start();

$adminName = "Admin";

$posts = [
    [
        "id" => 1,
        "title" => "Road Accident Near Airport",
        "owner" => "Rahim Ahmed",
        "date" => "20 Aug 2026",
        "content" => "A road accident occurred near the airport road. Authorities have been informed.",
        "status" => "Approved",
        "case_status" => "Taken",
        "deleted" => false
    ],
    [
        "id" => 2,
        "title" => "Broken Street Light",
        "owner" => "Karim Hasan",
        "date" => "19 Aug 2026",
        "content" => "Several street lights are not working in the residential area.",
        "status" => "Pending",
        "case_status" => "Pending",
        "deleted" => false
    ],
    [
        "id" => 3,
        "title" => "Garbage Problem",
        "owner" => "Nadia Islam",
        "date" => "18 Aug 2026",
        "content" => "Garbage has not been collected for several days.",
        "status" => "Rejected",
        "case_status" => "",
        "deleted" => true
    ]
];
?>

<html>
<head>
    <title>CivicLens Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
</head>

<body>

<div class="header">

    <div class="title">
        Dashboard — Admin
    </div>

    <div class="search-area">

        <input type="text" id="searchInput"
               placeholder="Search title, content, owner, CaseId">

        <button onclick="searchPosts()">Search</button>

        <button id="allButton"
                class="filter active"
                onclick="showAll()">
            All
        </button>

        <button id="trashButton"
                class="filter"
                onclick="showTrash()">
            Trash
        </button>

        <button onclick="refreshPage()">Refresh</button>

    </div>

</div>


<div class="page-container">

    <div class="sidebar">

        <div>

            <a href="case_status.php">
                Case Status
            </a>

            <a href="post_approval.php">
                Post Approval
            </a>

            <a href="moderator_list.php">
                Moderator List
            </a>

            <a href="project_team.php">
                Project Team Panel
            </a>

            <a href="admin_list.php">
                Admin List
            </a>

        </div>

        <a href="logout.php" class="logout">
            Logout
        </a>

    </div>


    <div class="main-content">

        <div class="welcome">

            <h2>
                Welcome, <?php echo $adminName; ?>
            </h2>

            <p>
                Manage CivicLens posts and administrative activities.
            </p>

        </div>


        <div id="postContainer">

            <?php foreach ($posts as $post) { ?>

                <div class="post-card"
                     data-id="<?php echo $post["id"]; ?>"
                     data-deleted="<?php echo $post["deleted"] ? "true" : "false"; ?>">

                    <h3>
                        <?php echo $post["title"]; ?>
                    </h3>

                    <p class="post-owner">
                        By: <?php echo $post["owner"]; ?>
                        •
                        <?php echo $post["date"]; ?>
                    </p>


                    <div class="status-area">

                        <?php
                        $statusClass = strtolower($post["status"]);
                        ?>

                        <span class="status <?php echo $statusClass; ?>">
                            <?php echo $post["status"]; ?>
                        </span>


                        <?php if ($post["case_status"] != "") { ?>

                            <span class="status case">
                                <?php echo $post["case_status"]; ?>
                            </span>

                        <?php } ?>


                        <?php if ($post["deleted"] == true) { ?>

                            <span class="status deleted">
                                Trash
                            </span>

                        <?php } ?>

                    </div>


                    <p class="post-content">
                        <?php echo $post["content"]; ?>
                    </p>


                    <div class="post-actions">

                        <?php if ($post["deleted"] == true) { ?>

                            <button class="restore-btn"
                                    onclick="restorePost(this)">
                                Restore
                            </button>

                        <?php } else { ?>

                            <button class="delete-btn"
                                    onclick="deletePost(this)">
                                Delete
                            </button>

                        <?php } ?>

                    </div>

                </div>

            <?php } ?>

        </div>

    </div>

</div>

<script src="admin.js"></script>

</body>
</html>