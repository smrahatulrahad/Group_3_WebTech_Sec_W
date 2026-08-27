<?php

session_start();

include "../Model/DatabaseConnection.php";


/* =========================
   LOGIN CHECK
========================= */

if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


/* =========================
   ROLE CHECK
========================= */

if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Police"
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


/* =========================
   DATABASE
========================= */

$database = new DatabaseConnection();
$connection = $database->openConnection();


/* =========================
   CURRENT POLICE USER
========================= */

$policeResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if (
    !$policeResult ||
    $policeResult->num_rows == 0
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$policeUser = $policeResult->fetch_assoc();


if (
    $policeUser["role"] != "Police" ||
    $policeUser["status"] != "Active"
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$policeId = (int)$policeUser["id"];
$policeName = $policeUser["fullname"];


/* Update login session */

$_SESSION["userName"] = $policeUser["fullname"];
$_SESSION["userEmail"] = $policeUser["email"];
$_SESSION["userRole"] = $policeUser["role"];


/* =========================
   CASE ACTION
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = (int)($_POST["post_id"] ?? 0);
    $action = $_POST["action"] ?? "";


    if ($postId > 0) {

        /* Check that post exists and is Approved */

        $postCheckStatement = $connection->prepare(
            "SELECT id
             FROM posts
             WHERE id = ?
             AND status = 'Approved'"
        );

        $postCheckStatement->bind_param(
            "i",
            $postId
        );

        $postCheckStatement->execute();

        $postCheckResult =
            $postCheckStatement->get_result();

        $postCheckStatement->close();


        if ($postCheckResult->num_rows > 0) {

            /* Get current police case */

            $caseStatement = $connection->prepare(
                "SELECT assigned_police_id, status
                 FROM police_cases
                 WHERE post_id = ?"
            );

            $caseStatement->bind_param(
                "i",
                $postId
            );

            $caseStatement->execute();

            $caseResult =
                $caseStatement->get_result();

            $currentCase = null;


            if ($caseResult->num_rows > 0) {
                $currentCase =
                    $caseResult->fetch_assoc();
            }

            $caseStatement->close();


            /* =========================
               TAKE
            ========================= */

            if ($action == "take") {

                /*
                    A Police officer can take an
                    unassigned case.

                    If another Police officer already
                    has it, this request is ignored.
                */

                if (
                    $currentCase == null ||
                    $currentCase["assigned_police_id"] == null
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "In Progress"
                    );
                }
            }


            /* =========================
               RELEASE
            ========================= */

            elseif ($action == "release") {

                if (
                    $currentCase != null &&
                    $currentCase["assigned_police_id"] != null &&
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        null,
                        "Open"
                    );
                }
            }


            /* =========================
               RESOLVE
            ========================= */

            elseif ($action == "resolve") {

                /*
                    Resolve if:
                    - no case record exists yet, or
                    - case is unassigned, or
                    - logged-in Police owns it.
                */

                if (
                    $currentCase == null ||
                    $currentCase["assigned_police_id"] == null ||
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "Resolved"
                    );
                }
            }


            /* =========================
               UNMARK RESOLVED
            ========================= */

            elseif ($action == "unresolve") {

                if (
                    $currentCase != null &&
                    $currentCase["status"] == "Resolved" &&
                    $currentCase["assigned_police_id"] != null &&
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "In Progress"
                    );
                }
            }
        }
    }


    $connection->close();

    header("Location: police.php");
    exit();
}


/* =========================
   GET POSTS
========================= */

$postsResult =
    $database->getAllPosts(
        $connection
    );

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
        CivicLens - Police Newsfeed
    </title>


    <!-- IMPORTANT: CSS IS INSIDE CSS FOLDER -->

    <link
        rel="stylesheet"
        href="CSS/police.css"
    >

</head>


<body>


<!-- =========================================
     HEADER
========================================= -->

<header class="header">


    <div class="header-title">

        Police Newsfeed

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


        <button
            type="button"
            onclick="refreshPage()"
        >
            Refresh
        </button>


    </div>



    <!-- TOP RIGHT -->

    <div class="header-right">


        <span class="officer-name">

            <?php

            echo htmlspecialchars(
                $policeName
            );

            ?>

        </span>

        <a
    href="../S.M. Rahatul Islam/Profile.php"
    class="profile-btn"
    >
    Profile
    </a>
        <a
            href="../utsa_Mazumdar/logout.php"
            class="logout-btn"
        >
            Logout
        </a>


    </div>


</header>



<!-- =========================================
     MAIN CONTENT
========================================= -->

<main class="main-content">


    <!-- =====================================
         PAGE TITLE
    ===================================== -->

    <section class="feed-title">


        <div>

            <h2>
                Reported Cases
            </h2>


            <p>
                View citizen complaints and manage assigned cases.
            </p>

        </div>



        <button
            type="button"
            class="show-cases-btn"
            onclick="window.location.href='../S.M. Rahatul Islam/ShowCases.php'"
        >
            Show Cases
        </button>


    </section>



    <!-- =====================================
         FILTERS
    ===================================== -->

    <div class="filters">


        <button
            type="button"
            id="allBtn"
            class="active"
            onclick="filterPosts('all')"
        >
            All
        </button>



        <button
            type="button"
            id="openBtn"
            onclick="filterPosts('Open')"
        >
            Open
        </button>



        <button
            type="button"
            id="progressBtn"
            onclick="filterPosts('In Progress')"
        >
            In Progress
        </button>



        <button
            type="button"
            id="resolvedBtn"
            onclick="filterPosts('Resolved')"
        >
            Resolved
        </button>



        <button
            type="button"
            id="emergencyBtn"
            onclick="filterPosts('Emergency')"
        >
            Emergency
        </button>


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


                /* Police sees approved posts */

                if (
                    $post["status"] != "Approved"
                ) {
                    continue;
                }


                $approvedPostFound = true;



                /* =========================
                   CITIZEN
                ========================= */

                $citizenName =
                    "Anonymous User";


                if (
                    (int)$post["anonymous"] == 0
                ) {

                    $citizenResult =
                        $database->getUserById(
                            $connection,
                            $post["user_id"]
                        );


                    if (
                        $citizenResult &&
                        $citizenResult->num_rows > 0
                    ) {
                        $citizen =
                            $citizenResult->fetch_assoc();

                        $citizenName =
                            $citizen["fullname"];
                    }
                }



                /* =========================
                   POLICE CASE
                ========================= */

                $caseStatus = "Open";
                $assignedPoliceId = null;
                $takenBy = "";


                $caseStatement =
                    $connection->prepare(
                        "SELECT
                            assigned_police_id,
                            status
                         FROM police_cases
                         WHERE post_id = ?"
                    );


                $caseStatement->bind_param(
                    "i",
                    $post["id"]
                );


                $caseStatement->execute();


                $caseResult =
                    $caseStatement->get_result();


                if (
                    $caseResult->num_rows > 0
                ) {

                    $case =
                        $caseResult->fetch_assoc();


                    $caseStatus =
                        $case["status"];

                    $assignedPoliceId =
                        $case["assigned_police_id"];


                    if (
                        $assignedPoliceId != null
                    ) {

                        if (
                            (int)$assignedPoliceId
                            == $policeId
                        ) {
                            $takenBy = "me";
                        }
                        else {
                            $takenBy = "other";
                        }
                    }
                }


                $caseStatement->close();



                /* =========================
                   EMERGENCY
                ========================= */

                $isEmergency = false;
                $emergencyClass = "";


                if (
                    $post["post_type"]
                    == "Emergency Post"
                ) {
                    $isEmergency = true;
                    $emergencyClass =
                        "emergency-card";
                }

        ?>


            <article
                class="post-card <?php
                    echo htmlspecialchars(
                        $emergencyClass
                    );
                ?>"

                data-status="<?php
                    echo htmlspecialchars(
                        $caseStatus
                    );
                ?>"

                data-emergency="<?php
                    echo $isEmergency
                        ? "true"
                        : "false";
                ?>"

                data-taken="<?php
                    echo htmlspecialchars(
                        $takenBy
                    );
                ?>"
            >



                <!-- =========================
                     POST HEADER
                ========================= -->

                <div class="post-header">


                    <div>


                        <h3>

                            <?php

                            echo htmlspecialchars(
                                $post["title"]
                            );

                            ?>

                        </h3>



                        <p class="citizen">

                            Citizen:

                            <strong>

                                <?php

                                echo htmlspecialchars(
                                    $citizenName
                                );

                                ?>

                            </strong>

                        </p>


                    </div>



                    <div class="right-info">


                        <span class="date">

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

                        </span>



                        <?php if ($isEmergency) { ?>


                            <span class="emergency-badge">

                                EMERGENCY

                            </span>


                        <?php } ?>


                    </div>


                </div>



                <!-- =========================
                     CONTENT
                ========================= -->

                <p class="content">

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $post["description"]
                        )
                    );

                    ?>

                </p>



                <!-- =========================
                     STATUS
                ========================= -->

                <div class="status-container">


                    <?php if ($caseStatus == "Resolved") { ?>


                        <span class="case-status resolved">

                            Resolved

                        </span>



                    <?php } elseif ($takenBy == "me") { ?>


                        <span class="case-status progress">

                            In Progress (You)

                        </span>



                    <?php } elseif ($takenBy == "other") { ?>


                        <span class="case-status taken">

                            Taken by another officer

                        </span>



                    <?php } else { ?>


                        <span class="case-status open">

                            Open

                        </span>


                    <?php } ?>


                </div>



                <!-- =========================
                     ACTIONS
                ========================= -->

                <div class="actions">



                    <!-- TAKE / RELEASE -->

                    <?php if ($takenBy == "me") { ?>


                        <form
                            action="police.php"
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
                                value="release"
                            >


                            <button
                                type="submit"
                                class="take-btn"
                            >
                                Release Case
                            </button>


                        </form>



                    <?php } elseif ($takenBy == "other") { ?>


                        <button
                            type="button"
                            class="take-btn"
                            disabled
                        >
                            Taken
                        </button>



                    <?php } else { ?>


                        <form
                            action="police.php"
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
                                value="take"
                            >


                            <button
                                type="submit"
                                class="take-btn"
                            >
                                Take Case
                            </button>


                        </form>


                    <?php } ?>



                    <!-- RESOLVE / UNRESOLVE -->

                    <?php if ($caseStatus == "Resolved") { ?>


                        <?php if ($takenBy == "me") { ?>


                            <form
                                action="police.php"
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
                                    value="unresolve"
                                >


                                <button
                                    type="submit"
                                    class="resolve-btn"
                                >
                                    Unmark Resolved
                                </button>


                            </form>


                        <?php } else { ?>


                            <button
                                type="button"
                                class="resolve-btn"
                                disabled
                            >
                                Resolved
                            </button>


                        <?php } ?>



                    <?php } else { ?>


                        <?php if ($takenBy == "other") { ?>


                            <button
                                type="button"
                                class="resolve-btn"
                                disabled
                            >
                                Mark Resolved
                            </button>



                        <?php } else { ?>


                            <form
                                action="police.php"
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
                                    value="resolve"
                                >


                                <button
                                    type="submit"
                                    class="resolve-btn"
                                >
                                    Mark Resolved
                                </button>


                            </form>


                        <?php } ?>


                    <?php } ?>


                </div>


            </article>


        <?php

            }

        }

        ?>


    </div>



    <!-- =====================================
         NO RESULT
    ===================================== -->

    <div
        id="noResult"
        class="no-result"
    >

        <?php

        if (!$approvedPostFound) {
            echo "No approved cases found.";
        }
        else {
            echo "No cases found.";
        }

        ?>

    </div>


</main>



<!-- =========================================
     FOOTER
========================================= -->

<footer class="footer">


    <span>

        CivicLens Police Portal

    </span>



    <a
        href="../S.M. Rahatul Islam/ShowCases.php"
        class="cases-btn"
    >
        Show Cases
    </a>


</footer>



<script src="JS/police.js"></script>


<?php

$connection->close();

?>


</body>

</html>