<?php
include "../Controller/Profile.php";
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
        CivicLens - Profile
    </title>

    <!-- <link
        rel="stylesheet"
        href="CSS/Profile.css"
    > -->

    <link rel="stylesheet" href="CSS/RahatulStyle.css">

</head>


<body class="profilePage">


<header class="header">


    <div class="brand">

        <h1>CivicLens</h1>

        <p>
            User Profile
        </p>

    </div>


    <a
        href="<?php echo $newsfeedPage; ?>"
        class="backButton"
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
            href="../Controller/logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageTitle">


            <div>

                <h2>
                    My Profile
                </h2>

                <p>
                    View your personal and account information.
                </p>

            </div>


            <a
                href="EditProfile.php"
                class="editButton"
            >
                Edit Profile
            </a>


        </div>



        <div class="profileCard">


            <div class="profileTop">


                <div class="largeAvatar">

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


                <div class="profileName">

                    <h3>

                        <?php
                        echo htmlspecialchars($userName);
                        ?>

                    </h3>


                    <span class="roleBadge">

                        <?php
                        echo htmlspecialchars($userRole);
                        ?>

                    </span>

                </div>


            </div>



            <div class="sectionTitle">

                <h3>
                    Basic Information
                </h3>

            </div>



            <div class="detailsGrid">


                <div class="detailItem">

                    <label>
                        Full Name
                    </label>

                    <p>

                        <?php
                        echo htmlspecialchars($userName);
                        ?>

                    </p>

                </div>



                <div class="detailItem">

                    <label>
                        Email Address
                    </label>

                    <p>

                        <?php
                        echo htmlspecialchars($userEmail);
                        ?>

                    </p>

                </div>



                <div class="detailItem">

                    <label>
                        Role
                    </label>

                    <p>

                        <?php
                        echo htmlspecialchars($userRole);
                        ?>

                    </p>

                </div>



                <div class="detailItem">

                    <label>
                        Phone Number
                    </label>

                    <p>

                        <?php

                        echo $userPhone != ""
                            ? htmlspecialchars($userPhone)
                            : "Not provided";

                        ?>

                    </p>

                </div>


            </div>



            <?php if ($userRole == "Citizen") { ?>


                <div class="sectionTitle secondSection">

                    <h3>
                        Citizen Information
                    </h3>

                </div>



                <div class="detailsGrid">


                    <div class="detailItem wideItem">

                        <label>
                            Address
                        </label>

                        <p>

                            <?php

                            echo $address != ""
                                ? htmlspecialchars($address)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            District
                        </label>

                        <p>

                            <?php

                            echo $district != ""
                                ? htmlspecialchars($district)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            Upazila
                        </label>

                        <p>

                            <?php

                            echo $upazila != ""
                                ? htmlspecialchars($upazila)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            NID Number
                        </label>

                        <p>

                            <?php

                            echo $nid != ""
                                ? htmlspecialchars($nid)
                                : "Not provided";

                            ?>

                        </p>

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


                    <div class="detailItem">

                        <label>
                            Journalist ID
                        </label>

                        <p>

                            <?php

                            echo $journalistId != ""
                                ? htmlspecialchars($journalistId)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            Channel / Organization
                        </label>

                        <p>

                            <?php

                            echo $channelName != ""
                                ? htmlspecialchars($channelName)
                                : "Not provided";

                            ?>

                        </p>

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


                    <div class="detailItem">

                        <label>
                            Badge Number
                        </label>

                        <p>

                            <?php

                            echo $badgeNumber != ""
                                ? htmlspecialchars($badgeNumber)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            Rank
                        </label>

                        <p>

                            <?php

                            echo $rank != ""
                                ? htmlspecialchars($rank)
                                : "Not provided";

                            ?>

                        </p>

                    </div>



                    <div class="detailItem">

                        <label>
                            Station Name
                        </label>

                        <p>

                            <?php

                            echo $stationName != ""
                                ? htmlspecialchars($stationName)
                                : "Not provided";

                            ?>

                        </p>

                    </div>


                </div>


            <?php } ?>


        </div>


    </main>


</div>


</body>

</html>