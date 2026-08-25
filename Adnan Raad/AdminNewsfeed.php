<?php
session_start();

$userName = $_SESSION["userName"] ?? "Admin User";

$view = $_GET["view"] ?? "all";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Admin Newsfeed</title>

    <link rel="stylesheet" href="AdminNewsfeed.css">

</head>


<body>


<header class="header">

    <div class="brand">

        <h1>CivicLens</h1>

        <p>Admin Newsfeed</p>

    </div>


    <form class="searchBox">

        <input
            type="text"
            placeholder="Search title, content, owner or case ID"
        >

        <button type="button">
            Search
        </button>

    </form>


    <a href="AdminNewsfeed.php" class="refreshButton">
        Refresh
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
                    <?php echo $userName; ?>
                </strong>

                <span>Admin</span>

            </div>

        </div>



        <div class="menu">

            <a href="AdminNewsfeed.php" class="active">
                Newsfeed
            </a>

            <a href="ShowCases.php">
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

        </div>


        <a href="#" class="logout">
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageHeading">

            <div>

                <h2>Admin Newsfeed</h2>

                <p>
                    Monitor posts, case activity and moderation status.
                </p>

            </div>


            <div class="viewButtons">

                <a
                    href="AdminNewsfeed.php?view=all"
                    class="<?php if ($view == "all") { echo "selected"; } ?>"
                >
                    All Posts
                </a>

                <a
                    href="AdminNewsfeed.php?view=trash"
                    class="<?php if ($view == "trash") { echo "selected"; } ?>"
                >
                    Trash
                </a>

            </div>

        </div>



        <?php if ($view == "all") { ?>


            <div class="postCard">


                <div class="postTop">

                    <div>

                        <h3>Broken Drain Cover Near School</h3>

                        <p>
                            By: Rahim Ahmed &nbsp; • &nbsp; 20 Aug 2026, 06:30 PM
                        </p>

                    </div>


                    <button type="button" class="trashButton">
                        Move to Trash
                    </button>

                </div>



                <div class="badges">

                    <span class="approved">
                        Approved
                    </span>

                    <span class="caseTaken">
                        Taken
                    </span>

                </div>



                <p class="postBody">
                    A drain cover near the school gate is broken and may cause accidents.
                    Please repair it as soon as possible.
                </p>



                <div class="activity">

                    <p>
                        <strong>Police:</strong>
                        Case Taken • 20 Aug, 08:00 PM
                    </p>

                    <p>
                        <strong>Journalist:</strong>
                        Samia Karim • 20 Aug, 09:15 PM
                    </p>

                    <p>
                        <strong>Approved:</strong>
                        20 Aug, 07:00 PM
                    </p>

                </div>


            </div>



            <div class="postCard emergencyCard">


                <div class="postTop">

                    <div>

                        <h3>Waterlogging After Heavy Rain</h3>

                        <p>
                            By: Nabila Islam &nbsp; • &nbsp; 20 Aug 2026, 04:10 PM
                        </p>

                    </div>


                    <button type="button" class="trashButton">
                        Move to Trash
                    </button>

                </div>



                <div class="badges">

                    <span class="pending">
                        Pending
                    </span>

                    <span class="caseOpen">
                        Open
                    </span>

                </div>



                <p class="postBody">
                    Heavy rain has caused severe waterlogging in the residential road.
                    People are having difficulty using the road.
                </p>



                <div class="activity">

                    <p>
                        <strong>Police:</strong>
                        No action yet
                    </p>

                    <p>
                        <strong>Journalist:</strong>
                        Not covered
                    </p>

                    <p>
                        <strong>Approved:</strong>
                        Not approved yet
                    </p>

                </div>


            </div>



            <div class="postCard">


                <div class="postTop">

                    <div>

                        <h3>Street Light Not Working</h3>

                        <p>
                            By: Tanvir Hasan &nbsp; • &nbsp; 19 Aug 2026, 10:05 PM
                        </p>

                    </div>


                    <button type="button" class="trashButton">
                        Move to Trash
                    </button>

                </div>



                <div class="badges">

                    <span class="approved">
                        Approved
                    </span>

                    <span class="resolved">
                        Resolved
                    </span>

                </div>



                <p class="postBody">
                    Several street lights beside the community park have not been working
                    for the last few days.
                </p>



                <div class="activity">

                    <p>
                        <strong>Police:</strong>
                        Case Resolved • 20 Aug, 02:30 PM
                    </p>

                    <p>
                        <strong>Journalist:</strong>
                        Farhan Kabir • 20 Aug, 11:10 AM
                    </p>

                    <p>
                        <strong>Approved:</strong>
                        20 Aug, 08:20 AM
                    </p>

                </div>


            </div>


        <?php } ?>



        <?php if ($view == "trash") { ?>


            <div class="trashNotice">

                Posts moved to Trash can be restored by an Admin or Moderator.

            </div>



            <div class="postCard trashCard">


                <div class="postTop">

                    <div>

                        <h3>Duplicate Road Complaint</h3>

                        <p>
                            By: Arif Hossain &nbsp; • &nbsp; 18 Aug 2026, 03:30 PM
                        </p>

                    </div>


                    <button type="button" class="restoreButton">
                        Restore
                    </button>

                </div>



                <div class="badges">

                    <span class="rejected">
                        Rejected
                    </span>

                    <span class="trashBadge">
                        Trash
                    </span>

                </div>



                <p class="postBody">
                    This report was moved to Trash because it was identified as a duplicate
                    of an existing civic report.
                </p>


            </div>



            <div class="postCard trashCard">


                <div class="postTop">

                    <div>

                        <h3>Incorrect Community Report</h3>

                        <p>
                            By: Anonymous User &nbsp; • &nbsp; 17 Aug 2026, 11:45 AM
                        </p>

                    </div>


                    <button type="button" class="restoreButton">
                        Restore
                    </button>

                </div>



                <div class="badges">

                    <span class="rejected">
                        Rejected
                    </span>

                    <span class="trashBadge">
                        Trash
                    </span>

                </div>



                <p class="postBody">
                    The submitted report did not contain enough valid information and was
                    moved to Trash.
                </p>


            </div>


        <?php } ?>


    </main>


</div>


</body>

</html>