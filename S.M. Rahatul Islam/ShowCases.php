<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true ||
    !isset($_SESSION["userId"])
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$database = new DatabaseConnection();

$connection = $database->openConnection();



$userResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if ($userResult->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$user = $userResult->fetch_assoc();


if ($user["status"] == "Disabled") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userName = $user["fullname"];
$userRole = $user["role"];


// Only valid project roles 

if (
    $userRole != "Citizen" &&
    $userRole != "Police" &&
    $userRole != "Journalist" &&
    $userRole != "Admin" &&
    $userRole != "Moderator"
) {

    $connection->close();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


// login session information updated 

$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $user["email"];



   //CORRECT NEWSFEED
   

$newsfeedPage = "UserNewsfeed.php";


if ($userRole == "Journalist") {

    $newsfeedPage =
        "../Adnan Raad/journalist.php";

}


elseif ($userRole == "Police") {

    $newsfeedPage =
        "../Adnan Raad/police.php";

}


elseif (
    $userRole == "Admin" ||
    $userRole == "Moderator"
) {

    $newsfeedPage =
        "../Adnan Raad/AdminNewsfeed.php";

}



$sql =
    "SELECT
        posts.id,
        posts.title,
        police_cases.status AS police_status,
        COUNT(journalist_coverage.id) AS coverage_count
     FROM posts

     LEFT JOIN police_cases
        ON posts.id = police_cases.post_id

     LEFT JOIN journalist_coverage
        ON posts.id = journalist_coverage.post_id

     WHERE posts.status = 'Approved'

     GROUP BY
        posts.id,
        posts.title,
        police_cases.status

     ORDER BY posts.id DESC";


$result = $connection->query($sql);

$cases = [];


while ($row = $result->fetch_assoc()) {


    /* Police case status has priority */

    if ($row["police_status"] != null) {

        $caseStatus =
            $row["police_status"];

    }


    /* Journalist is covering the post */

    elseif (
        (int) $row["coverage_count"] > 0
    ) {

        $caseStatus =
            "Covered";

    }


    /* Approved but not taken yet */

    else {

        $caseStatus =
            "Open";

    }


    $row["case_status"] =
        $caseStatus;


    $cases[] = $row;

}


$connection->close();


$caseCount = count($cases);

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
        CivicLens - Show Cases
    </title>

    <link
        rel="stylesheet"
        href="CSS/ShowCases.css"
    >

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Case Status</p>

    </div>


    <a
        href="<?php echo $newsfeedPage; ?>"
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


            <?php if (
                $userRole == "Admin" ||
                $userRole == "Moderator"
            ) { ?>


                <a href="../Adnan Raad/AdminNewsfeed.php">
                    Newsfeed
                </a>


                <a
                    href="ShowCases.php"
                    class="active"
                >
                    Case Status
                </a>


                <a href="../utsa_Mazumdar/PostApproval.php">
                    Post Approval
                </a>


                <a href="../utsa_Mazumdar/StaffManagement.php">
                    Staff Management
                </a>


                <a href="../Adnan Raad/UserManagement.php">
                    User Management
                </a>


            <?php } else { ?>


                <a href="<?php echo $newsfeedPage; ?>">
                    Newsfeed
                </a>


                <a href="Profile.php">
                    Profile
                </a>


                <?php if ($userRole == "Citizen") { ?>

                    <a href="PendingPosts.php">
                        Pending Posts
                    </a>

                <?php } ?>


                <a
                    href="ShowCases.php"
                    class="active"
                >
                    Show Cases
                </a>


                <?php if ($userRole == "Citizen") { ?>

                    <a href="Donation.php">
                        Donation
                    </a>

                <?php } ?>


            <?php } ?>


        </div>


        <a
            href="../utsa_Mazumdar/logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <h2>
                Case Status
            </h2>

            <p>
                View the current status of reported civic cases.
            </p>

        </div>



        <div class="caseCard">


            <div class="tableHeader">

                <h3>
                    Cases
                </h3>

                <span>

                    <?php

                    echo $caseCount;

                    echo $caseCount == 1
                        ? " Case"
                        : " Cases";

                    ?>

                </span>

            </div>



            <div class="tableWrapper">


                <table>


                    <thead>

                        <tr>

                            <th>
                                Case ID
                            </th>

                            <th>
                                Title
                            </th>

                            <th>
                                Status
                            </th>

                        </tr>

                    </thead>



                    <tbody>


                        <?php if ($caseCount == 0) { ?>


                            <tr>

                                <td colspan="3">

                                    No approved cases found.

                                </td>

                            </tr>


                        <?php } ?>



                        <?php foreach ($cases as $case) { ?>


                            <?php


                            $statusClass = "open";


                            if (
                                $case["case_status"] ==
                                "In Progress"
                            ) {

                                $statusClass =
                                    "progress";

                            }


                            elseif (
                                $case["case_status"] ==
                                "Resolved"
                            ) {

                                $statusClass =
                                    "resolved";

                            }


                            elseif (
                                $case["case_status"] ==
                                "Covered"
                            ) {

                                $statusClass =
                                    "covered";

                            }


                            ?>


                            <tr>


                                <td>

                                    #<?php
                                    echo (int) $case["id"];
                                    ?>

                                </td>



                                <td>


                                    <a
                                        href="<?php echo $newsfeedPage; ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $case["title"]
                                        );

                                        ?>

                                    </a>


                                </td>



                                <td>


                                    <span
                                        class="status <?php echo $statusClass; ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $case["case_status"]
                                        );

                                        ?>

                                    </span>


                                </td>


                            </tr>


                        <?php } ?>


                    </tbody>


                </table>


            </div>


        </div>


    </main>


</div>


</body>

</html>