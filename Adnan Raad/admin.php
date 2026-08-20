<?php
session_start();

/*
Later, after login is connected, you can use:

if (!isset($_SESSION['role']) || $_SESSION['role'] != "Admin") {
    header("Location: login.php");
    exit();
}

$adminName = $_SESSION['name'];
*/

$adminName = "Admin";

// Temporary post data.
// Later this can come from MySQL database.
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>CivicLens Admin Dashboard</title>

    <link rel="stylesheet" href="admin.css">
</head>

<body>

<!-- ================= HEADER ================= -->

<header class="header">

    <div class="title">
        Dashboard — Admin
    </div>

    <div class="search-area">

        <input
            type="text"
            id="searchInput"
            placeholder="Search title, content, owner, CaseId"
        >

        <button onclick="searchPosts()">
            Search
        </button>

        <button
            id="allButton"
            class="filter active"
            onclick="showAll()">
            All
        </button>

        <button
            id="trashButton"
            class="filter"
            onclick="showTrash()">
            Trash
        </button>

        <button onclick="refreshPage()">
            Refresh
        </button>

    </div>

</header>


<!-- ================= PAGE ================= -->

<div class="page-container">


    <!-- ================= SIDEBAR ================= -->

    <aside class="sidebar">

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

    </aside>



    <!-- ================= MAIN CONTENT ================= -->

    <main class="main-content">

        <div class="welcome">

            <h2>
                Welcome, <?php echo htmlspecialchars($adminName); ?>
            </h2>

            <p>
                Manage CivicLens posts and administrative activities.
            </p>

        </div>


        <!-- POSTS -->

        <div id="postContainer">

            <?php foreach ($posts as $post): ?>

                <div
                    class="post-card"

                    data-id="<?php echo $post['id']; ?>"

                    data-deleted="<?php
                        echo $post['deleted'] ? 'true' : 'false';
                    ?>"
                >

                    <h3>
                        <?php
                        echo htmlspecialchars($post['title']);
                        ?>
                    </h3>


                    <p class="post-owner">

                        By:
                        <?php
                        echo htmlspecialchars($post['owner']);
                        ?>

                        •

                        <?php
                        echo htmlspecialchars($post['date']);
                        ?>

                    </p>


                    <!-- STATUS -->

                    <div class="status-area">

                        <?php
                        $statusClass =
                            strtolower($post['status']);
                        ?>

                        <span
                            class="status <?php echo $statusClass; ?>"
                        >
                            <?php
                            echo htmlspecialchars($post['status']);
                            ?>
                        </span>


                        <?php
                        if (!empty($post['case_status'])):
                        ?>

                            <span class="status case">

                                <?php
                                echo htmlspecialchars(
                                    $post['case_status']
                                );
                                ?>

                            </span>

                        <?php endif; ?>


                        <?php
                        if ($post['deleted']):
                        ?>

                            <span class="status deleted">
                                Trash
                            </span>

                        <?php endif; ?>

                    </div>


                    <!-- CONTENT -->

                    <p class="post-content">

                        <?php
                        echo htmlspecialchars(
                            $post['content']
                        );
                        ?>

                    </p>


                    <!-- ACTION -->

                    <div class="post-actions">

                        <?php if ($post['deleted']): ?>

                            <button
                                class="restore-btn"
                                onclick="restorePost(this)"
                            >
                                Restore
                            </button>

                        <?php else: ?>

                            <button
                                class="delete-btn"
                                onclick="deletePost(this)"
                            >
                                Delete
                            </button>

                        <?php endif; ?>

                    </div>

                </div>

            <?php endforeach; ?>

        </div>

    </main>

</div>


<script src="admin.js"></script>

</body>

</html>