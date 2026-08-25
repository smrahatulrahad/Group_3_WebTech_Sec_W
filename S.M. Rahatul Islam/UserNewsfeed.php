<?php
session_start();

$userName = $_SESSION["userName"] ?? "Nafis Rahman";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicLens - User Newsfeed</title>
    <link rel="stylesheet" href="CSS/UserNewsfeed.css">
</head>
<body>

<header class="header">

    <div class="brand">
        <h1>CivicLens</h1>
        <p>Community Newsfeed</p>
    </div>

    <form class="searchBox">
        <input type="text" placeholder="Search posts...">
        <button type="button">Search</button>
    </form>

    <div class="headerButtons">
        <a href="CreatePost.php" class="createPost">+ Post</a>
        <a href="UserNewsfeed.php" class="refreshButton">Refresh</a>
    </div>

</header>

<div class="pageContainer">

    <aside class="sidebar">

        <div class="userInfo">

            <div class="avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>

            <div>
                <small>Signed in as</small>
                <strong><?php echo $userName; ?></strong>
            </div>

        </div>

        <div class="menu">

            <a href="UserNewsfeed.php" class="active">Newsfeed</a>
            <a href="Profile.php">Profile</a>
            <a href="PendingPosts.php">Pending Posts</a>
            <a href="ShowCases.php">Show Cases</a>

            <details>
                <summary>Donation</summary>
                <a href="Donation.php" class="subMenu">Money Donation</a>
                <a href="Donation.php" class="subMenu">Blood Donation</a>
            </details>

        </div>

        <a href="../utsa_Mazumdar/View/login.php" class="logout">Logout</a>

    </aside>

    <main class="mainContent">

        <div class="feedHeader">

            <div>
                <h2>Community Newsfeed</h2>
                <p>Approved civic reports and community updates.</p>
            </div>

            <span class="postCount">3 Posts</span>

        </div>


        <div class="postCard">

            <div class="postHeading">

                <div>
                    <h3>Road Damage Near Main Intersection</h3>

                    <p class="postInformation">
                        By Rahim Ahmed <span>•</span> 20 Aug 2026, 09:40 PM
                    </p>
                </div>

            </div>

            <span class="activity">Case is being reviewed</span>

            <div class="postContent">

                <p>
                    A large pothole has formed near the main intersection.
                    It is causing traffic problems and may become dangerous at night.
                </p>

                <div class="mediaBox">
                    <span>Image attached</span>
                </div>

            </div>

        </div>


        <div class="postCard emergencyPost">

            <div class="postHeading">

                <div>
                    <h3>Urgent Help Needed After Local Fire</h3>

                    <p class="postInformation">
                        By Samia Karim <span>•</span> 20 Aug 2026, 08:15 PM
                    </p>
                </div>

                <span class="emergencyBadge">EMERGENCY</span>

            </div>

            <span class="activity">Emergency post reported</span>

            <div class="postContent">

                <p>
                    A small fire was reported in a residential area.
                    Local residents have already contacted emergency services.
                    Please avoid blocking the road.
                </p>

            </div>

        </div>


        <div class="postCard">

            <div class="postHeading">

                <div>
                    <h3>Street Light Not Working</h3>

                    <p class="postInformation">
                        By Tanvir Hasan <span>•</span> 19 Aug 2026, 10:05 PM
                    </p>
                </div>

            </div>

            <span class="activity">Post approved</span>

            <div class="postContent">

                <p>
                    Several street lights beside the community park have not been working
                    for the last few days. The area becomes very dark after sunset.
                </p>

                <div class="mediaBox">
                    <span>Video attached</span>
                    <button type="button">Play Video</button>
                </div>

            </div>

        </div>

    </main>

</div>

</body>
</html>