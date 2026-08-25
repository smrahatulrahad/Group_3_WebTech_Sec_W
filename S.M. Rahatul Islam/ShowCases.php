<?php
session_start();


if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userName = $_SESSION["userName"];
$userRole = $_SESSION["userRole"];

$newsfeedPage = "UserNewsfeed.php";

if ($userRole == "Journalist") {
    $newsfeedPage = "../Adnan Raad/journalist.php";
}

if ($userRole == "Police") {
    $newsfeedPage = "../Adnan Raad/police.php";
}

if ($userRole == "Admin" || $userRole == "Moderator") {
    $newsfeedPage = "../Adnan Raad/AdminNewsfeed.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Show Cases</title>
    <link rel="stylesheet" href="CSS/ShowCases.css">

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Case Status</p>

    </div>


    <a href="<?php echo $newsfeedPage; ?>" class="backTop">
        Back to Newsfeed
    </a>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">

            <div class="avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>

            <div>

                <small>Signed in as</small>

                <strong>
                    <?php echo htmlspecialchars($userName); ?>
                </strong>

                <span>
                    <?php echo htmlspecialchars($userRole); ?>
                </span>

            </div>

        </div>



        <div class="menu">


            <?php if ($userRole == "Admin" || $userRole == "Moderator") { ?>


                <a href="../Adnan Raad/AdminNewsfeed.php">
                    Newsfeed
                </a>

                <a href="ShowCases.php" class="active">
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


                <a href="ShowCases.php" class="active">
                    Show Cases
                </a>


                <?php if ($userRole == "Citizen") { ?>

                    <a href="Donation.php">
                        Donation
                    </a>

                <?php } ?>


            <?php } ?>


        </div>


        <a href="../utsa_Mazumdar/logout.php" class="logout">Logout</a>

    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <h2>Case Status</h2>

            <p>
                View the current status of reported civic cases.
            </p>

        </div>



        <div class="caseCard">


            <div class="tableHeader">

                <h3>Cases</h3>

                <span>4 Cases</span>

            </div>




            <div class="tableWrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Case ID</th>

                            <th>Title</th>

                            <th>Status</th>

                        </tr>

                    </thead>



                    <tbody>


                        <tr>

                            <td>#1001</td>

                            <td>
                                <a href="<?php echo $newsfeedPage; ?>">
                                    Broken Drain Cover Near School
                                </a>
                            </td>

                            <td>
                                <span class="status open">
                                    Open
                                </span>
                            </td>

                        </tr>



                        <tr>

                            <td>#1002</td>

                            <td>
                                <a href="<?php echo $newsfeedPage; ?>">
                                    Waterlogging After Heavy Rain
                                </a>
                            </td>

                            <td>
                                <span class="status progress">
                                    In Progress
                                </span>
                            </td>

                        </tr>



                        <tr>

                            <td>#1003</td>

                            <td>
                                <a href="<?php echo $newsfeedPage; ?>">
                                    Street Light Not Working
                                </a>
                            </td>

                            <td>
                                <span class="status resolved">
                                    Resolved
                                </span>
                            </td>

                        </tr>



                        <tr>

                            <td>#1004</td>

                            <td>
                                <a href="<?php echo $newsfeedPage; ?>">
                                    Unsafe Construction Material on Road
                                </a>
                            </td>

                            <td>
                                <span class="status covered">
                                    Covered
                                </span>
                            </td>

                        </tr>


                    </tbody>


                </table>


            </div>


        </div>


    </main>


</div>


</body>

</html>