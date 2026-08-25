<?php
session_start();


if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


if ($_SESSION["userRole"] != "Citizen") {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userName = $_SESSION["userName"];
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Donation</title>

    <link rel="stylesheet" href="CSS/Donation.css">

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Donation</p>

    </div>

    <a href="UserNewsfeed.php" class="backTop">
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

            </div>

        </div>


        <div class="menu">

            <a href="UserNewsfeed.php">
                Newsfeed
            </a>

            <a href="Profile.php">
                Profile
            </a>

            <a href="PendingPosts.php">
                Pending Posts
            </a>

            <a href="ShowCases.php">
                Show Cases
            </a>

            <a href="Donation.php" class="active">
                Donation
            </a>

        </div>


        <a href="../utsa_Mazumdar/logout.php" class="logout">
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <h2>Donation Resources</h2>

            <p>
                Find trusted organizations for money donation and blood support.
            </p>

        </div>



        <section class="donationSection">


            <div class="sectionHeader">

                <h3>Money Donation</h3>

                <p>
                    Select an organization to visit its donation page.
                </p>

            </div>



            <div class="linkList">


                <a href="https://www.brac.net/donate" target="_blank">
                    <strong>BRAC</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://bidyanondo.org/donate" target="_blank">
                    <strong>Bidyanondo Foundation</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://jaago.com.bd/donate" target="_blank">
                    <strong>JAAGO Foundation</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://assunnahfoundation.org/donate" target="_blank">
                    <strong>As-Sunnah Foundation</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://www.crp-bangladesh.org/donate" target="_blank">
                    <strong>Centre for the Rehabilitation of the Paralysed (CRP)</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://relief.gov.bd/donate" target="_blank">
                    <strong>Sylhet Flood Relief Portal</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://bdrcs.org/donate" target="_blank">
                    <strong>Red Crescent Bangladesh</strong>
                    <span>Visit donation page</span>
                </a>


                <a href="https://dhakaahsanianmission.org.bd/donate" target="_blank">
                    <strong>Dhaka Ahsania Mission</strong>
                    <span>Visit donation page</span>
                </a>


            </div>


        </section>



        <div class="bloodGrid">


            <section class="donationSection">


                <div class="sectionHeader">

                    <h3>Donate Blood</h3>

                    <p>
                        Blood donation organizations and services.
                    </p>

                </div>



                <div class="linkList">


                    <a href="https://bdrcs.org/" target="_blank">
                        <strong>Red Crescent Society (BD)</strong>
                        <span>Open website</span>
                    </a>


                    <a href="https://badhan.org/" target="_blank">
                        <strong>Badhan</strong>
                        <span>Open website</span>
                    </a>


                    <a href="https://www.sandhani.org.bd/" target="_blank">
                        <strong>Sandhani</strong>
                        <span>Open website</span>
                    </a>


                    <a href="https://quantumfoundationbd.com/blood" target="_blank">
                        <strong>Quantum Blood Bank</strong>
                        <span>Open website</span>
                    </a>


                    <a href="https://muktibloodbank.org/" target="_blank">
                        <strong>Mukti Blood Bank</strong>
                        <span>Open website</span>
                    </a>


                    <a href="https://www.bloodman.org/" target="_blank">
                        <strong>Bloodman Bangladesh</strong>
                        <span>Open website</span>
                    </a>


                </div>


            </section>



            <section class="donationSection">


                <div class="sectionHeader">

                    <h3>Request / Find Blood</h3>

                    <p>
                        Services that can help you find blood donors.
                    </p>

                </div>



                <div class="linkList">


                    <a href="https://badhan.org/find-donor" target="_blank">
                        <strong>Badhan - Need Blood</strong>
                        <span>Find donor</span>
                    </a>


                    <a href="https://quantumfoundationbd.com/blood-request" target="_blank">
                        <strong>Quantum - Request Blood</strong>
                        <span>Request blood</span>
                    </a>


                    <a href="https://bdrcs.org/contact" target="_blank">
                        <strong>Red Crescent - Contact</strong>
                        <span>Contact organization</span>
                    </a>


                    <a href="https://shohozroktodan.com/" target="_blank">
                        <strong>Shohoz Roktodan</strong>
                        <span>Find blood support</span>
                    </a>


                    <a href="https://www.facebook.com/groups/bdblooddonors/" target="_blank">
                        <strong>Bangladesh Blood Donors</strong>
                        <span>Open community group</span>
                    </a>


                </div>


            </section>

        </div>


    </main>


</div>


</body>

</html>