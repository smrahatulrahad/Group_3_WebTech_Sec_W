<?php

session_start();



$registrationError = "";

if (isset($_SESSION["registrationError"])) {

    $registrationError = $_SESSION["registrationError"];

    unset($_SESSION["registrationError"]);
}

?>



<!DOCTYPE html>
<html>

<head>

    <title>CivicLens - Registration</title>

    <link rel="stylesheet" href="CSS/style.css">

</head>

<body>

<div class="register-window">


    <div class="register-header">

        CivicLens - Create New Account

    </div>


    <div class="register-content">

        <!-- LEFT SIDE -->

        <div class="left-side">

            <h1>
                WELCOME TO<br>
                CIVICLENS
            </h1>

            <p>
                Multimedia Complaint Management
                Tracking System
            </p>


            <h2>
                Select Account Type
            </h2>


            <div class="role-box">

                <!-- Citizen is selected first -->
                <input type="radio"
                       name="roleChoice"
                       value="citizen"
                       onclick="citizen()"
                       checked>

                Citizen


                <br><br>


                <input type="radio"
                       name="roleChoice"
                       value="police"
                       onclick="police()">

                Police


                <br><br>


                <input type="radio"
                       name="roleChoice"
                       value="journalist"
                       onclick="journalist()">

                Journalist

            </div>


            <p class="choose-message">

                Citizen account is selected by default.

            </p>

        </div>



        <!-- RIGHT SIDE -->

        <div class="right-side">


            <h1>
                Create a new account
            </h1>



            <?php

            if ($registrationError != "") {

                echo "<p class='login_error'>";

                echo htmlspecialchars($registrationError);

                echo "</p>";

            }

            ?>


            <form method="post"
                  action="registrationValidation.php"
                  id="registrationForm"
                  onsubmit="return validateRegistration()">


                <!-- This stores the selected role -->

                <input type="hidden"
                       name="role"
                       id="selectedRole"
                       value="citizen">


                <!-- COMMON INFORMATION -->

                <div class="form-grid">


                    <span>
                        Full Name:
                    </span>

                    <input type="text"
                           name="fullname"
                           id="fullname"
                           required>



                    <span>
                        Email Address:
                    </span>

                    <input type="email"
                           name="email"
                           id="email"
                           required>



                    <span>
                        New Password:
                    </span>

                    <input type="password"
                           name="password"
                           id="password"
                           required>



                    <span>
                        Phone Number:
                    </span>

                    <input type="text"
                           name="phone"
                           id="phone"
                           required>

                </div>



                <!-- CITIZEN -->

                <div id="citizenFields"
                     class="role-fields">


                    <h2>
                        Address Information
                    </h2>


                    <div class="form-grid">


                        <span>
                            District:
                        </span>

                        <input type="text"
                               name="district"
                               id="district">



                        <span>
                            Upazila:
                        </span>

                        <input type="text"
                               name="upazila"
                               id="upazila">



                        <span>
                            Municipality / Union:
                        </span>

                        <input type="text"
                               name="union"
                               id="union">


                        <span>
                            Area:
                        </span>

                        <input type="text"
                               name="area"
                               id="area">



                        <span>
                            National ID:
                        </span>

                        <input type="text"
                               name="nid"
                               id="nid">


                    </div>

                </div>



                <!-- POLICE -->

                <div id="policeFields"
                     class="role-fields hidden">


                    <h2>
                        Police Information
                    </h2>


                    <div class="form-grid">


                        <span>
                            Rank:
                        </span>

                        <input type="text"
                               name="rank"
                               id="rank">


                        <span>
                            Station Name:
                        </span>

                        <input type="text"
                               name="station"
                               id="station">



                        <span>
                            Badge Number:
                        </span>

                        <input type="text"
                               name="badge"
                               id="badge">


                    </div>

                </div>



                <!-- JOURNALIST -->

                <div id="journalistFields"
                     class="role-fields hidden">


                    <h2>
                        Journalist Information
                    </h2>


                    <div class="form-grid">


                        <span>
                            Channel Name:
                        </span>

                        <input type="text"
                               name="channel"
                               id="channel">


                        <span>
                            Journalist ID:
                        </span>

                        <input type="text"
                               name="journalist_id"
                               id="journalist_id">


                    </div>

                </div>



                <!-- SECURITY QUESTIONS -->

                <div class="role-fields">


                    <h2>
                        Security Questions
                    </h2>


                    <div class="form-grid">


                        <span>
                            Favorite movie?
                        </span>

                        <input type="text"
                               name="q1"
                               id="q1"
                               required>



                        <span>
                            Favorite sports team?
                        </span>

                        <input type="text"
                               name="q2"
                               id="q2"
                               required>


                        <span>
                            Childhood hero?
                        </span>

                        <input type="text"
                               name="q3"
                               id="q3"
                               required>


                    </div>

                </div>



                <!-- BUTTONS -->

                <div class="buttons">


                    <a href="login.php"
                       class="back">

                        Back

                    </a>


                    <button type="submit"
                            class="next">

                        Create Account

                    </button>


                </div>


            </form>

        </div>

    </div>

</div>


<script src="JS/script.js"></script>

</body>

</html>