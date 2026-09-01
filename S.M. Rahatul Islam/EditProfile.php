<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


if (!isset($_SESSION["userId"])) {

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userId = (int) $_SESSION["userId"];
$errorMessage = "";




$database = new DatabaseConnection();

$connection = $database->openConnection();



   //GET CURRENT USER
  

$result = $database->getUserById(
    $connection,
    $userId
);


if ($result->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$user = $result->fetch_assoc();


if ($user["status"] == "Disabled") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userRole = $user["role"];


// This profile page is only for these roles 

if (
    $userRole != "Citizen" &&
    $userRole != "Police" &&
    $userRole != "Journalist"
) {

    $connection->close();

    header("Location: ../Adnan Raad/AdminNewsfeed.php");
    exit();
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userName = trim(
        $_POST["userName"] ?? ""
    );

    $userPhone = trim(
        $_POST["userPhone"] ?? ""
    );


    if (
        $userName == "" ||
        $userPhone == ""
    ) {

        $errorMessage =
            "Please complete all required fields.";

    }

    elseif (!ctype_digit($userPhone)) {

        $errorMessage =
            "Phone number must contain numbers only.";

    }

    else {


       
          // CITIZEN
           

        if ($userRole == "Citizen") {

            $address = trim(
                $_POST["address"] ?? ""
            );

            $district = trim(
                $_POST["district"] ?? ""
            );

            $upazila = trim(
                $_POST["upazila"] ?? ""
            );

            $nid = trim(
                $_POST["nid"] ?? ""
            );


            if (
                $address == "" ||
                $district == "" ||
                $upazila == "" ||
                $nid == ""
            ) {

                $errorMessage =
                    "Please complete all Citizen information.";

            }

            else {

                $sql =
                    "UPDATE users
                     SET fullname = ?,
                         phone = ?,
                         address = ?,
                         district = ?,
                         upazila = ?,
                         nid = ?
                     WHERE id = ?";


                $statement =
                    $connection->prepare($sql);


                $statement->bind_param(
                    "ssssssi",
                    $userName,
                    $userPhone,
                    $address,
                    $district,
                    $upazila,
                    $nid,
                    $userId
                );

            }

        }


        
           //JOURNALIST
           

        elseif ($userRole == "Journalist") {

            $journalistId = trim(
                $_POST["journalistId"] ?? ""
            );

            $channelName = trim(
                $_POST["channelName"] ?? ""
            );


            if (
                $journalistId == "" ||
                $channelName == ""
            ) {

                $errorMessage =
                    "Please complete all Journalist information.";

            }

            else {

                $sql =
                    "UPDATE users
                     SET fullname = ?,
                         phone = ?,
                         journalist_id = ?,
                         channel_name = ?
                     WHERE id = ?";


                $statement =
                    $connection->prepare($sql);


                $statement->bind_param(
                    "ssssi",
                    $userName,
                    $userPhone,
                    $journalistId,
                    $channelName,
                    $userId
                );

            }

        }


        
          // POLICE
           

        else {

            $badgeNumber = trim(
                $_POST["badgeNumber"] ?? ""
            );

            $rank = trim(
                $_POST["rank"] ?? ""
            );

            $stationName = trim(
                $_POST["stationName"] ?? ""
            );


            if (
                $badgeNumber == "" ||
                $rank == "" ||
                $stationName == ""
            ) {

                $errorMessage =
                    "Please complete all Police information.";

            }

            else {

                $sql =
                    "UPDATE users
                     SET fullname = ?,
                         phone = ?,
                         badge_number = ?,
                         police_rank = ?,
                         station_name = ?
                     WHERE id = ?";


                $statement =
                    $connection->prepare($sql);


                $statement->bind_param(
                    "sssssi",
                    $userName,
                    $userPhone,
                    $badgeNumber,
                    $rank,
                    $stationName,
                    $userId
                );

            }

        }


       

        if (
            $errorMessage == "" &&
            isset($statement)
        ) {

            if ($statement->execute()) {

                $statement->close();

               

                $_SESSION["userName"] =
                    $userName;

                $connection->close();

                header("Location: Profile.php");
                exit();

            }

            else {

                $errorMessage =
                    "Profile could not be updated. Please try again.";

                $statement->close();

            }

        }

    }

}



  // GET LATEST USER INFORMATION
  

$result = $database->getUserById(
    $connection,
    $userId
);

$user = $result->fetch_assoc();


$userName = $user["fullname"];
$userEmail = $user["email"];
$userPhone = $user["phone"];


/* Citizen information */

$address = $user["address"] ?? "";
$district = $user["district"] ?? "";
$upazila = $user["upazila"] ?? "";
$nid = $user["nid"] ?? "";


// Journalist information 

$journalistId =
    $user["journalist_id"] ?? "";

$channelName =
    $user["channel_name"] ?? "";


// Police information 

$badgeNumber =
    $user["badge_number"] ?? "";

$rank =
    $user["police_rank"] ?? "";

$stationName =
    $user["station_name"] ?? "";


// Keep normal login session updated 

$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $userEmail;


$connection->close();



 //  CORRECT NEWSFEED
  

$newsfeedPage = "UserNewsfeed.php";


if ($userRole == "Journalist") {

    $newsfeedPage =
        "../Adnan Raad/journalist.php";

}


elseif ($userRole == "Police") {

    $newsfeedPage =
        "../Adnan Raad/police.php";

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CivicLens - Edit Profile</title>

    <!-- <link
        rel="stylesheet"
        href="CSS/EditProfile.css"
    > -->
    <link rel="stylesheet" href="CSS/style.css">
</head>

<body class="editProfilePage">

<header class="header">

    <div class="brand">
        <h1>CivicLens</h1>
        <p>Edit Profile</p>
    </div>

    <a
        href="Profile.php"
        class="backButton"
    >
        Back to Profile
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
                    echo htmlspecialchars($userName);
                    ?>
                </strong>

                <span>
                    <?php
                    echo htmlspecialchars($userRole);
                    ?>
                </span>

            </div>

        </div>


        <div class="menu">

            <a href="<?php echo $newsfeedPage; ?>">
                Newsfeed
            </a>

            <a
                href="Profile.php"
                class="active"
            >
                Profile
            </a>


            <?php if ($userRole == "Citizen") { ?>

                <a href="PendingPosts.php">
                    Pending Posts
                </a>

            <?php } ?>


            <a href="ShowCases.php">
                Show Cases
            </a>


            <?php if ($userRole == "Citizen") { ?>

                <a href="Donation.php">
                    Donation
                </a>

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
                Edit Profile
            </h2>

            <p>
                Update your personal and account information.
            </p>


            <?php if ($errorMessage != "") { ?>

                <p>

                    <?php
                    echo htmlspecialchars(
                        $errorMessage
                    );
                    ?>

                </p>

            <?php } ?>

        </div>



        <form
            action="EditProfile.php"
            method="post"
            class="profileCard"
        >


            <div class="sectionTitle">

                <h3>
                    Basic Information
                </h3>

            </div>


            <div class="detailsGrid">

                <div class="formItem">

                    <label>
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="userName"
                        value="<?php echo htmlspecialchars($userName); ?>"
                        required
                    >

                </div>


                <div class="formItem">

                    <label>
                        Email Address
                    </label>

                    <input
                        type="email"
                        value="<?php echo htmlspecialchars($userEmail); ?>"
                        readonly
                    >

                </div>


                <div class="formItem">

                    <label>
                        Role
                    </label>

                    <input
                        type="text"
                        value="<?php echo htmlspecialchars($userRole); ?>"
                        readonly
                    >

                </div>


                <div class="formItem">

                    <label>
                        Phone Number
                    </label>

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

                    <h3>
                        Citizen Information
                    </h3>

                </div>


                <div class="detailsGrid">


                    <div class="formItem wideItem">

                        <label>
                            Address
                        </label>

                        <input
                            type="text"
                            name="address"
                            value="<?php echo htmlspecialchars($address); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            District
                        </label>

                        <input
                            type="text"
                            name="district"
                            value="<?php echo htmlspecialchars($district); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            Upazila
                        </label>

                        <input
                            type="text"
                            name="upazila"
                            value="<?php echo htmlspecialchars($upazila); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            NID Number
                        </label>

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

                    <h3>
                        Journalist Information
                    </h3>

                </div>


                <div class="detailsGrid">


                    <div class="formItem">

                        <label>
                            Journalist ID
                        </label>

                        <input
                            type="text"
                            name="journalistId"
                            value="<?php echo htmlspecialchars($journalistId); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            Channel / Organization
                        </label>

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

                    <h3>
                        Police Information
                    </h3>

                </div>


                <div class="detailsGrid">


                    <div class="formItem">

                        <label>
                            Badge Number
                        </label>

                        <input
                            type="text"
                            name="badgeNumber"
                            value="<?php echo htmlspecialchars($badgeNumber); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            Rank
                        </label>

                        <input
                            type="text"
                            name="rank"
                            value="<?php echo htmlspecialchars($rank); ?>"
                            required
                        >

                    </div>


                    <div class="formItem">

                        <label>
                            Station Name
                        </label>

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

                <button
                    type="submit"
                    class="saveButton"
                >
                    Save Changes
                </button>

                <a
                    href="Profile.php"
                    class="cancelButton"
                >
                    Cancel
                </a>

            </div>


        </form>


    </main>

</div>

</body>

</html>