<?php
session_start();

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


/* Save edited information */
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $_SESSION["userName"] = trim($_POST["userName"]);
    //$_SESSION["userEmail"] = trim($_POST["userEmail"]);
    $_SESSION["userPhone"] = trim($_POST["userPhone"]);


    if ($userRole == "Citizen") {

        $_SESSION["address"] = trim($_POST["address"]);
        $_SESSION["district"] = trim($_POST["district"]);
        $_SESSION["upazila"] = trim($_POST["upazila"]);
        $_SESSION["nid"] = trim($_POST["nid"]);

    }


    if ($userRole == "Journalist") {

        $_SESSION["journalistId"] = trim($_POST["journalistId"]);
        $_SESSION["channelName"] = trim($_POST["channelName"]);

    }


    if ($userRole == "Police") {

        $_SESSION["badgeNumber"] = trim($_POST["badgeNumber"]);
        $_SESSION["rank"] = trim($_POST["rank"]);
        $_SESSION["stationName"] = trim($_POST["stationName"]);

    }


    header("Location: Profile.php");
    exit();
}


/* Choose the correct newsfeed according to role */
$newsfeedPage = "UserNewsfeed.php";

if ($userRole == "Journalist") {
    $newsfeedPage = "../Adnan Raad/journalist.php";
}

if ($userRole == "Police") {
    $newsfeedPage = "../Adnan Raad/police.php";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Edit Profile</title>
    <link rel="stylesheet" href="CSS/EditProfile.css">
</head>

<body>

<header class="header">

    <div class="brand">
        <h1>CivicLens</h1>
        <p>Edit Profile</p>
    </div>

    <a href="Profile.php" class="backButton">Back to Profile</a>

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


            <?php if ($userRole == "Citizen") { ?>

                <a href="PendingPosts.php">Pending Posts</a>

            <?php } ?>


            <a href="ShowCases.php">Show Cases</a>


            <?php if ($userRole == "Citizen") { ?>

                <a href="Donation.php">Donation</a>

            <?php } ?>

        </div>


        <a href="../utsa_Mazumdar/View/login.php" class="logout">Logout</a>

    </aside>



    <main class="mainContent">

        <div class="pageTitle">

            <h2>Edit Profile</h2>

            <p>Update your personal and account information.</p>

        </div>



        <form action="EditProfile.php" method="post" class="profileCard">


            <div class="sectionTitle">
                <h3>Basic Information</h3>
            </div>


            <div class="detailsGrid">

                <div class="formItem">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="userName"
                        value="<?php echo htmlspecialchars($userName); ?>"
                        required
                    >

                </div>


                <div class="formItem">

                    <label>Email Address</label>

                   <input
    type="email"
    value="<?php echo htmlspecialchars($userEmail); ?>"
    readonly
>

                </div>


                <div class="formItem">

                    <label>Role</label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($userRole); ?>"
                        readonly
                    >

                </div>


                <div class="formItem">

                    <label>Phone Number</label>

                    <input
                        type="text"
                        name="userPhone"
                        value="<?php echo htmlspecialchars($userPhone); ?>"
                        required
                    >

                </div>

            </div>



            <?php if ($userRole == "Citizen") { ?>


                <div class="sectionTitle secondSection">
                    <h3>Citizen Information</h3>
                </div>


                <div class="detailsGrid">


                    <div class="formItem wideItem">

                        <label>Address</label>

                        <input
                            type="text"
                            name="address"
                            value="<?php echo htmlspecialchars($address); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>District</label>

                        <input
                            type="text"
                            name="district"
                            value="<?php echo htmlspecialchars($district); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>Upazila</label>

                        <input
                            type="text"
                            name="upazila"
                            value="<?php echo htmlspecialchars($upazila); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>NID Number</label>

                        <input
                            type="text"
                            name="nid"
                            value="<?php echo htmlspecialchars($nid); ?>"
                            required
                        >

                    </div>


                </div>


            <?php } ?>



            <?php if ($userRole == "Journalist") { ?>


                <div class="sectionTitle secondSection">
                    <h3>Journalist Information</h3>
                </div>


                <div class="detailsGrid">


                    <div class="formItem">

                        <label>Journalist ID</label>

                        <input
                            type="text"
                            name="journalistId"
                            value="<?php echo htmlspecialchars($journalistId); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>Channel / Organization</label>

                        <input
                            type="text"
                            name="channelName"
                            value="<?php echo htmlspecialchars($channelName); ?>"
                            required
                        >

                    </div>


                </div>


            <?php } ?>



            <?php if ($userRole == "Police") { ?>


                <div class="sectionTitle secondSection">
                    <h3>Police Information</h3>
                </div>


                <div class="detailsGrid">


                    <div class="formItem">

                        <label>Badge Number</label>

                        <input
                            type="text"
                            name="badgeNumber"
                            value="<?php echo htmlspecialchars($badgeNumber); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>Rank</label>

                        <input
                            type="text"
                            name="rank"
                            value="<?php echo htmlspecialchars($rank); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>Station Name</label>

                        <input
                            type="text"
                            name="stationName"
                            value="<?php echo htmlspecialchars($stationName); ?>"
                            required
                        >

                    </div>


                </div>


            <?php } ?>



            <div class="formButtons">

                <button type="submit" class="saveButton">
                    Save Changes
                </button>

                <a href="Profile.php" class="cancelButton">
                    Cancel
                </a>

            </div>


        </form>


    </main>

</div>

</body>

</html>