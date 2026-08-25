<?php
session_start();

$policeName = $_SESSION["userName"] ?? "Police Officer";

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


/* Store case information in SESSION */
if (!isset($_SESSION["policeCases"])) {

    $_SESSION["policeCases"] = [];

    foreach ($posts as $post) {

        $_SESSION["policeCases"][$post["id"]] = [
            "status" => $post["status"],
            "taken_by" => $post["taken_by"]
        ];

    }

}


/* Take, Release or Resolve Case */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $caseId = (int) ($_POST["caseId"] ?? 0);
    $action = $_POST["action"] ?? "";


    if (isset($_SESSION["policeCases"][$caseId])) {

        $case = $_SESSION["policeCases"][$caseId];


        if ($action == "take") {

            if (
                $case["taken_by"] == "" &&
                $case["status"] != "Resolved"
            ) {

                $_SESSION["policeCases"][$caseId]["taken_by"] = "me";
                $_SESSION["policeCases"][$caseId]["status"] = "In Progress";

            }

        }


        if ($action == "release") {

            if ($case["taken_by"] == "me") {

                $_SESSION["policeCases"][$caseId]["taken_by"] = "";
                $_SESSION["policeCases"][$caseId]["status"] = "Open";

            }

        }


        if ($action == "resolve") {

            if ($case["taken_by"] == "me") {

                $_SESSION["policeCases"][$caseId]["status"] = "Resolved";

            }

        }


        if ($action == "unresolve") {

            if ($case["taken_by"] == "me") {

                $_SESSION["policeCases"][$caseId]["status"] = "In Progress";

            }

        }

    }


    header("Location: police.php");
    exit();

}


/* Update posts from SESSION */
foreach ($posts as $key => $post) {

    if (isset($_SESSION["policeCases"][$post["id"]])) {

        $posts[$key]["status"] =
            $_SESSION["policeCases"][$post["id"]]["status"];

        $posts[$key]["taken_by"] =
            $_SESSION["policeCases"][$post["id"]]["taken_by"];

    }

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
            stripos($post["citizen"], $searchText) === false
        ) {

            $showPost = false;

        }

    }


    if (
        $filter != "all" &&
        $filter != "Emergency" &&
        $post["status"] != $filter
    ) {

        $showPost = false;

    }


    if (
        $filter == "Emergency" &&
        $post["emergency"] == false
    ) {

        $showPost = false;

    }


    if ($showPost == true) {

        $filteredPosts[] = $post;

    }

}
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



    <form
        class="search-area"
        action="police.php"
        method="get"
    >


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
                All
            </option>


            <option
                value="Open"
                <?php
                if ($filter == "Open") {
                    echo "selected";
                }
                ?>
            >
                Open
            </option>


            <option
                value="In Progress"
                <?php
                if ($filter == "In Progress") {
                    echo "selected";
                }
                ?>
            >
                In Progress
            </option>


            <option
                value="Resolved"
                <?php
                if ($filter == "Resolved") {
                    echo "selected";
                }
                ?>
            >
                Resolved
            </option>


            <option
                value="Emergency"
                <?php
                if ($filter == "Emergency") {
                    echo "selected";
                }
                ?>
            >
                Emergency
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


        <span class="officer-name">

            <?php echo htmlspecialchars($policeName); ?>

        </span>


        <a href="police.php" class="profile-btn">

            Newsfeed

        </a>


        <a
            href="../S.M. Rahatul Islam/Profile.php"
            class="profile-btn"
        >

            Profile

        </a>


        <a
            href="../S.M. Rahatul Islam/ShowCases.php"
            class="show-cases-btn"
        >

            Show Cases

        </a>


        <a
            href="../utsa_Mazumdar/View/login.php"
            class="logout-btn"
        >

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



    <div class="result-bar">

        <span>

            Cases

        </span>

        <span>

            <?php echo count($filteredPosts); ?> cases

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
            >


                <div class="post-header">


                    <div>


                        <h3>

                            <?php echo htmlspecialchars($post["title"]); ?>

                        </h3>


                        <p class="citizen">

                            Citizen:

                            <strong>

                                <?php echo htmlspecialchars($post["citizen"]); ?>

                            </strong>

                        </p>


                    </div>



                    <div class="right-info">


                        <span class="date">

                            <?php echo htmlspecialchars($post["date"]); ?>

                        </span>


                        <?php if ($post["emergency"] == true) { ?>


                            <span class="emergency-badge">

                                EMERGENCY

                            </span>


                        <?php } ?>


                    </div>


                </div>



                <p class="content">

                    <?php echo htmlspecialchars($post["content"]); ?>

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


                    <?php if ($post["status"] != "Resolved") { ?>


                        <?php if ($post["taken_by"] == "me") { ?>


                            <form action="police.php" method="post">

                                <input
                                    type="hidden"
                                    name="caseId"
                                    value="<?php echo $post["id"]; ?>"
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


                        <?php } elseif ($post["taken_by"] == "other") { ?>


                            <button
                                type="button"
                                class="take-btn"
                                disabled
                            >

                                Case Taken

                            </button>


                        <?php } else { ?>


                            <form action="police.php" method="post">

                                <input
                                    type="hidden"
                                    name="caseId"
                                    value="<?php echo $post["id"]; ?>"
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


                    <?php } ?>



                    <?php if ($post["taken_by"] == "me") { ?>


                        <form action="police.php" method="post">

                            <input
                                type="hidden"
                                name="caseId"
                                value="<?php echo $post["id"]; ?>"
                            >


                            <?php if ($post["status"] == "Resolved") { ?>


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


                            <?php } else { ?>


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


                            <?php } ?>


                        </form>


                    <?php } ?>


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

            No cases found.

        </div>


    <?php } ?>


</div>



<div class="footer">

    <span>

        CivicLens Police Portal

    </span>

</div>


</body>

</html>