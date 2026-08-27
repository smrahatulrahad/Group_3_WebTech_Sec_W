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
    (
        $_SESSION["userRole"] != "Admin" &&
        $_SESSION["userRole"] != "Moderator"
    )
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
   CURRENT USER
========================= */

$userResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if (
    !$userResult ||
    $userResult->num_rows == 0
) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();

}


$currentUser = $userResult->fetch_assoc();


if ($currentUser["status"] != "Active") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();

}


$userName = $currentUser["fullname"];
$userRole = $currentUser["role"];


$_SESSION["userName"] = $userName;
$_SESSION["userEmail"] = $currentUser["email"];
$_SESSION["userRole"] = $userRole;


/* =========================
   MOVE TO TRASH / RESTORE
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = (int)($_POST["post_id"] ?? 0);

    $action = $_POST["action"] ?? "";


    if ($postId > 0) {


        /* Move post to Trash */

        if ($action == "trash") {

            $newStatus = "Rejected";

            $statement = $connection->prepare(
                "UPDATE posts
                 SET status = ?,
                     reviewed_by = ?,
                     reviewed_at = NOW()
                 WHERE id = ?"
            );

            $statement->bind_param(
                "sii",
                $newStatus,
                $_SESSION["userId"],
                $postId
            );

            $statement->execute();

            $statement->close();


            header(
                "Location: AdminNewsfeed.php?view=trash"
            );

            exit();

        }


        /* Restore post */

        elseif ($action == "restore") {

            $newStatus = "Pending";

            $statement = $connection->prepare(
                "UPDATE posts
                 SET status = ?,
                     reviewed_by = NULL,
                     reviewed_at = NULL
                 WHERE id = ?"
            );

            $statement->bind_param(
                "si",
                $newStatus,
                $postId
            );

            $statement->execute();

            $statement->close();


            header(
                "Location: AdminNewsfeed.php?view=all"
            );

            exit();

        }

    }

}


/* =========================
   VIEW
========================= */

$view = $_GET["view"] ?? "all";


if (
    $view != "all" &&
    $view != "trash"
) {

    $view = "all";

}


/* =========================
   SEARCH
========================= */

$search = trim(
    $_GET["search"] ?? ""
);


/* =========================
   POSTS
========================= */

$postsResult = $database->getAllPosts(
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

    <title>CivicLens - Admin Newsfeed</title>

    <link
        rel="stylesheet"
        href="CSS/AdminNewsfeed.css"
    >

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<header class="header">


    <div class="brand">

        <h1>
            CivicLens
        </h1>

        <p>
            Admin Newsfeed
        </p>

    </div>



    <form
        class="searchBox"
        action="AdminNewsfeed.php"
        method="get"
    >

        <input
            type="hidden"
            name="view"
            value="<?php
                echo htmlspecialchars($view);
            ?>"
        >


        <input
            type="text"
            name="search"
            placeholder="Search title, content, owner or case ID"
            value="<?php
                echo htmlspecialchars($search);
            ?>"
        >


        <button type="submit">
            Search
        </button>

    </form>



    <a
        href="AdminNewsfeed.php"
        class="refreshButton"
    >
        Refresh
    </a>


</header>



<div class="pageContainer">


    <!-- =========================
         SIDEBAR
    ========================= -->

    <aside class="sidebar">


        <div class="userInfo">


            <div class="avatar">

                <?php

                echo htmlspecialchars(
                    strtoupper(
                        substr(
                            $userName,
                            0,
                            1
                        )
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


                <span>

                    <?php

                    echo htmlspecialchars(
                        $userRole
                    );

                    ?>

                </span>


            </div>


        </div>



        <div class="menu">


            <a
                href="AdminNewsfeed.php"
                class="active"
            >
                Newsfeed
            </a>


            <a
                href="../S.M. Rahatul Islam/ShowCases.php"
            >
                Case Status
            </a>


            <a
                href="../utsa_Mazumdar/PostApproval.php"
            >
                Post Approval
            </a>


            <a
                href="../utsa_Mazumdar/StaffManagement.php"
            >
                Staff Management
            </a>


            <a
                href="UserManagement.php"
            >
                User Management
            </a>


        </div>



        <a
            href="../utsa_Mazumdar/logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>



    <!-- =========================
         MAIN CONTENT
    ========================= -->

    <main class="mainContent">


        <div class="pageHeading">


            <div>

                <h2>
                    Admin Newsfeed
                </h2>

                <p>
                    Monitor posts, case activity and moderation status.
                </p>

            </div>



            <div class="viewButtons">


                <a
                    href="AdminNewsfeed.php?view=all"
                    class="<?php
                        if ($view == "all") {
                            echo "selected";
                        }
                    ?>"
                >
                    All Posts
                </a>


                <a
                    href="AdminNewsfeed.php?view=trash"
                    class="<?php
                        if ($view == "trash") {
                            echo "selected";
                        }
                    ?>"
                >
                    Trash
                </a>


            </div>


        </div>



        <?php

        $postFound = false;


        if (
            $postsResult &&
            $postsResult->num_rows > 0
        ) {


            while (
                $post =
                $postsResult->fetch_assoc()
            ) {


                /* =========================
                   VIEW FILTER
                ========================= */

                if (
                    $view == "all" &&
                    $post["status"] == "Rejected"
                ) {

                    continue;

                }


                if (
                    $view == "trash" &&
                    $post["status"] != "Rejected"
                ) {

                    continue;

                }



                /* =========================
                   OWNER
                ========================= */

                $ownerName =
                    "Anonymous User";


                if (
                    (int)$post["anonymous"] == 0
                ) {


                    $ownerResult =
                        $database->getUserById(
                            $connection,
                            $post["user_id"]
                        );


                    if (
                        $ownerResult &&
                        $ownerResult->num_rows > 0
                    ) {

                        $owner =
                            $ownerResult->fetch_assoc();

                        $ownerName =
                            $owner["fullname"];

                    }

                }



                /* =========================
                   POLICE CASE
                ========================= */

                $caseStatus =
                    "Open";

                $caseBadgeClass =
                    "caseOpen";

                $caseBadgeText =
                    "Open";

                $policeActivity =
                    "No action yet";


                $caseStatement =
                    $connection->prepare(
                        "SELECT
                            police_cases.status,
                            police_cases.assigned_police_id,
                            police_cases.updated_at,
                            users.fullname AS police_name
                         FROM police_cases
                         LEFT JOIN users
                            ON police_cases.assigned_police_id = users.id
                         WHERE police_cases.post_id = ?"
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


                    if (
                        $case["status"] ==
                        "Resolved"
                    ) {


                        $caseBadgeClass =
                            "resolved";

                        $caseBadgeText =
                            "Resolved";

                        $policeActivity =
                            "Case Resolved";


                        if (
                            !empty(
                                $case["police_name"]
                            )
                        ) {

                            $policeActivity .=
                                " by " .
                                $case["police_name"];

                        }


                    }


                    elseif (
                        $case["assigned_police_id"]
                        != null
                    ) {


                        $caseBadgeClass =
                            "caseTaken";

                        $caseBadgeText =
                            "Taken";

                        $policeActivity =
                            "In Progress";


                        if (
                            !empty(
                                $case["police_name"]
                            )
                        ) {

                            $policeActivity .=
                                " by " .
                                $case["police_name"];

                        }


                    }


                    else {


                        $caseBadgeClass =
                            "caseOpen";

                        $caseBadgeText =
                            "Open";

                        $policeActivity =
                            "No action yet";


                    }


                    if (
                        !empty(
                            $case["updated_at"]
                        )
                    ) {

                        $policeActivity .=
                            " • " .
                            date(
                                "d M, h:i A",
                                strtotime(
                                    $case["updated_at"]
                                )
                            );

                    }


                }


                $caseStatement->close();



                /* =========================
                   JOURNALIST
                ========================= */

                $journalistActivity =
                    "Not covered";


                $coverageStatement =
                    $connection->prepare(
                        "SELECT
                            users.fullname,
                            journalist_coverage.covered_at
                         FROM journalist_coverage
                         INNER JOIN users
                            ON journalist_coverage.journalist_id = users.id
                         WHERE journalist_coverage.post_id = ?
                         ORDER BY journalist_coverage.covered_at DESC"
                    );


                $coverageStatement->bind_param(
                    "i",
                    $post["id"]
                );


                $coverageStatement->execute();


                $coverageResult =
                    $coverageStatement->get_result();


                if (
                    $coverageResult->num_rows > 0
                ) {


                    $journalistNames =
                        array();

                    $latestCoverageTime =
                        "";


                    while (
                        $coverage =
                        $coverageResult->fetch_assoc()
                    ) {


                        $journalistNames[] =
                            $coverage["fullname"];


                        if (
                            $latestCoverageTime == ""
                        ) {

                            $latestCoverageTime =
                                $coverage["covered_at"];

                        }

                    }


                    $journalistActivity =
                        implode(
                            ", ",
                            $journalistNames
                        );


                    if (
                        $latestCoverageTime != ""
                    ) {

                        $journalistActivity .=
                            " • " .
                            date(
                                "d M, h:i A",
                                strtotime(
                                    $latestCoverageTime
                                )
                            );

                    }


                }


                $coverageStatement->close();



                /* =========================
                   SEARCH
                ========================= */

                if ($search != "") {


                    $searchText =
                        strtolower(
                            $post["id"] .
                            " " .
                            $post["title"] .
                            " " .
                            $post["description"] .
                            " " .
                            $ownerName .
                            " " .
                            $caseStatus
                        );


                    if (
                        strpos(
                            $searchText,
                            strtolower($search)
                        ) === false
                    ) {

                        continue;

                    }

                }



                $postFound = true;



                /* =========================
                   POST CARD CLASS
                ========================= */

                $postCardClass =
                    "postCard";


                if (
                    $post["post_type"]
                    == "Emergency Post"
                ) {

                    $postCardClass .=
                        " emergencyCard";

                }


                if ($view == "trash") {

                    $postCardClass .=
                        " trashCard";

                }



                /* =========================
                   STATUS CLASS
                ========================= */

                $statusClass =
                    "pending";


                if (
                    $post["status"] ==
                    "Approved"
                ) {

                    $statusClass =
                        "approved";

                }


                elseif (
                    $post["status"] ==
                    "Rejected"
                ) {

                    $statusClass =
                        "rejected";

                }



                /* =========================
                   MODERATION ACTIVITY
                ========================= */

                $reviewActivity =
                    $post["status"];


                if (
                    !empty(
                        $post["reviewed_at"]
                    )
                ) {

                    $reviewActivity .=
                        " • " .
                        date(
                            "d M, h:i A",
                            strtotime(
                                $post["reviewed_at"]
                            )
                        );

                }


                elseif (
                    $post["status"] ==
                    "Pending"
                ) {

                    $reviewActivity =
                        "Not reviewed yet";

                }

        ?>


            <div
                class="<?php
                    echo htmlspecialchars(
                        $postCardClass
                    );
                ?>"
            >


                <div class="postTop">


                    <div>


                        <h3>

                            <?php

                            echo htmlspecialchars(
                                $post["title"]
                            );

                            ?>

                        </h3>


                        <p>

                            By:

                            <?php

                            echo htmlspecialchars(
                                $ownerName
                            );

                            ?>

                            &nbsp; • &nbsp;

                            <?php

                            echo htmlspecialchars(
                                date(
                                    "d M Y, h:i A",
                                    strtotime(
                                        $post["created_at"]
                                    )
                                )
                            );

                            ?>

                        </p>


                    </div>



                    <?php

                    if ($view == "all") {

                    ?>


                        <form
                            action="AdminNewsfeed.php"
                            method="post"
                            onsubmit="return confirm('Move this post to Trash?');"
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
                                value="trash"
                            >


                            <button
                                type="submit"
                                class="trashButton"
                            >
                                Move to Trash
                            </button>


                        </form>


                    <?php

                    }
                    else {

                    ?>


                        <form
                            action="AdminNewsfeed.php"
                            method="post"
                            onsubmit="return confirm('Restore this post?');"
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
                                value="restore"
                            >


                            <button
                                type="submit"
                                class="restoreButton"
                            >
                                Restore
                            </button>


                        </form>


                    <?php

                    }

                    ?>


                </div>



                <div class="badges">


                    <span
                        class="<?php
                            echo htmlspecialchars(
                                $statusClass
                            );
                        ?>"
                    >

                        <?php

                        echo htmlspecialchars(
                            $post["status"]
                        );

                        ?>

                    </span>



                    <?php

                    if ($view == "trash") {

                    ?>

                        <span class="trashBadge">
                            Trash
                        </span>

                    <?php

                    }
                    else {

                    ?>


                        <span
                            class="<?php
                                echo htmlspecialchars(
                                    $caseBadgeClass
                                );
                            ?>"
                        >

                            <?php

                            echo htmlspecialchars(
                                $caseBadgeText
                            );

                            ?>

                        </span>


                    <?php

                    }

                    ?>


                </div>



                <p class="postBody">

                    <?php

                    echo nl2br(
                        htmlspecialchars(
                            $post["description"]
                        )
                    );

                    ?>

                </p>



                <?php

                if ($view == "all") {

                ?>


                    <div class="activity">


                        <p>

                            <strong>
                                Police:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $policeActivity
                            );

                            ?>

                        </p>


                        <p>

                            <strong>
                                Journalist:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $journalistActivity
                            );

                            ?>

                        </p>


                        <p>

                            <strong>
                                Moderation:
                            </strong>

                            <?php

                            echo htmlspecialchars(
                                $reviewActivity
                            );

                            ?>

                        </p>


                    </div>


                <?php

                }

                ?>


            </div>


        <?php

            }

        }

        ?>



        <?php

        if (!$postFound) {

        ?>


            <div class="postCard">


                <p class="postBody">


                    <?php

                    if ($search != "") {

                        echo "No posts found for your search.";

                    }


                    elseif ($view == "trash") {

                        echo "Trash is empty.";

                    }


                    else {

                        echo "No posts are available.";

                    }

                    ?>


                </p>


            </div>


        <?php

        }

        ?>


    </main>


</div>


<?php

$connection->close();

?>


</body>

</html>