<?php
include "../Controller/ShowCases.php";
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

    <!-- <link
        rel="stylesheet"
        href="CSS/ShowCases.css"
    > -->
    <link rel="stylesheet" href="CSS/RahatulStyle.css">

</head>


<body class="showCasesPage">


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


                <a href="AdminNewsfeed.php">
                    Newsfeed
                </a>


                <a
                    href="ShowCases.php"
                    class="active"
                >
                    Case Status
                </a>


                <a href="PostApproval.php">
                    Post Approval
                </a>


                <a href="StaffManagement.php">
                    Staff Management
                </a>


                <a href="UserManagement.php">
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
            href="../Controller/logout.php"
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