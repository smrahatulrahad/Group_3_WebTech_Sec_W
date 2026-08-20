<?php
session_start();

/*
    No database is used yet.
    If the Login page creates these SESSION values, this page will show them.
    Otherwise the simple demo values below are shown.
*/

$userName = $_SESSION["userName"] ?? "Nafis Rahman";
$userRole = $_SESSION["userRole"] ?? "Citizen";
$userEmail = $_SESSION["userEmail"] ?? "nafis.rahman@gmail.com";
$userPhone = $_SESSION["userPhone"] ?? "01700-000000";

/* Citizen information */
$address = $_SESSION["address"] ?? "Bashundhara Residential Area";
$district = $_SESSION["district"] ?? "Dhaka";
$upazila = $_SESSION["upazila"] ?? "Badda";
$nid = $_SESSION["nid"] ?? "1234567890";

/* Journalist information */
$journalistId = $_SESSION["journalistId"] ?? "JRN-1025";
$channelName = $_SESSION["channelName"] ?? "Daily News Network";

/* Police information */
$badgeNumber = $_SESSION["badgeNumber"] ?? "BDP-2048";
$rank = $_SESSION["rank"] ?? "Sub-Inspector";
$stationName = $_SESSION["stationName"] ?? "Badda Police Station";

/* Choose the correct newsfeed according to role */
$newsfeedPage = "UserNewsfeed.php";

if ($userRole == "Journalist") {
    $newsfeedPage = "JournalistNewsfeed.php";
}

if ($userRole == "Police") {
    $newsfeedPage = "PoliceNewsfeed.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CivicLens - Profile</title>
    <link rel="stylesheet" href="CSS/Profile.css">
</head>

<body>

<header class="header">

    <div class="brand">
        <h1>CivicLens</h1>
        <p>User Profile</p>
    </div>

    <a href="<?php echo $newsfeedPage; ?>" class="backButton">Back to Newsfeed</a>

</header>


<div class="pageContainer">

    <aside class="sidebar">

        <div class="userInfo">

            <div class="avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>

            <div>
                <small>Signed in as</small>
                <strong><?php echo htmlspecialchars($userName); ?></strong>
                <span><?php echo htmlspecialchars($userRole); ?></span>
            </div>

        </div>

        <div class="menu">
            <a href="<?php echo $newsfeedPage; ?>">Newsfeed</a>
            <a href="Profile.php" class="active">Profile</a>
            <a href="#">Pending Posts</a>
            <a href="#">Show Cases</a>
        </div>

        <a href="#" class="logout">Logout</a>

    </aside>


    <main class="mainContent">

        <div class="pageTitle">
            <div>
                <h2>My Profile</h2>
                <p>View your personal and account information.</p>
            </div>

            <a href="#" class="editButton">Edit Profile</a>
        </div>


        <div class="profileCard">

            <div class="profileTop">

                <div class="largeAvatar">
                    <?php echo strtoupper(substr($userName, 0, 1)); ?>
                </div>

                <div class="profileName">
                    <h3><?php echo htmlspecialchars($userName); ?></h3>
                    <span class="roleBadge"><?php echo htmlspecialchars($userRole); ?></span>
                </div>

            </div>


            <div class="sectionTitle">
                <h3>Basic Information</h3>
            </div>

            <div class="detailsGrid">

                <div class="detailItem">
                    <label>Full Name</label>
                    <p><?php echo htmlspecialchars($userName); ?></p>
                </div>

                <div class="detailItem">
                    <label>Email Address</label>
                    <p><?php echo htmlspecialchars($userEmail); ?></p>
                </div>

                <div class="detailItem">
                    <label>Role</label>
                    <p><?php echo htmlspecialchars($userRole); ?></p>
                </div>

                <div class="detailItem">
                    <label>Phone Number</label>
                    <p><?php echo htmlspecialchars($userPhone); ?></p>
                </div>

            </div>


            <?php if ($userRole == "Citizen") { ?>

                <div class="sectionTitle secondSection">
                    <h3>Citizen Information</h3>
                </div>

                <div class="detailsGrid">

                    <div class="detailItem wideItem">
                        <label>Address</label>
                        <p><?php echo htmlspecialchars($address); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>District</label>
                        <p><?php echo htmlspecialchars($district); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>Upazila</label>
                        <p><?php echo htmlspecialchars($upazila); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>NID Number</label>
                        <p><?php echo htmlspecialchars($nid); ?></p>
                    </div>

                </div>

            <?php } ?>


            <?php if ($userRole == "Journalist") { ?>

                <div class="sectionTitle secondSection">
                    <h3>Journalist Information</h3>
                </div>

                <div class="detailsGrid">

                    <div class="detailItem">
                        <label>Journalist ID</label>
                        <p><?php echo htmlspecialchars($journalistId); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>Channel / Organization</label>
                        <p><?php echo htmlspecialchars($channelName); ?></p>
                    </div>

                </div>

            <?php } ?>


            <?php if ($userRole == "Police") { ?>

                <div class="sectionTitle secondSection">
                    <h3>Police Information</h3>
                </div>

                <div class="detailsGrid">

                    <div class="detailItem">
                        <label>Badge Number</label>
                        <p><?php echo htmlspecialchars($badgeNumber); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>Rank</label>
                        <p><?php echo htmlspecialchars($rank); ?></p>
                    </div>

                    <div class="detailItem">
                        <label>Station Name</label>
                        <p><?php echo htmlspecialchars($stationName); ?></p>
                    </div>

                </div>

            <?php } ?>

        </div>

    </main>

</div>

</body>
</html>
