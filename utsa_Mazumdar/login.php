<?php

session_start();


$emailError = "";

$passwordError = "";

$loginError = "";


if (isset($_SESSION["emailError"])) {

    $emailError = $_SESSION["emailError"];

    unset($_SESSION["emailError"]);

}


if (isset($_SESSION["passwordError"])) {

    $passwordError = $_SESSION["passwordError"];

    unset($_SESSION["passwordError"]);

}


if (isset($_SESSION["loginError"])) {

    $loginError = $_SESSION["loginError"];

    unset($_SESSION["loginError"]);

}

?>

<!DOCTYPE html>
<html>

<head>

    <title>CivicLens - Sign In</title>

    <link rel="stylesheet" href="CSS/style.css">

</head>



<body>


<div class="login_window">


    <div class="login_header">

        CivicLens - Sign In

    </div>



    <div class="login_content">


        <h1 id="login_title">

            WELCOME TO CIVICLENS

        </h1>


        <p id="login_subtitle">

            Multimedia Complaint Management
            Tracking System

        </p>



        <div class="login_form_box">


            <?php

            if ($loginError != "") {

                echo "<p class='login_error'>";

                echo htmlspecialchars($loginError);

                echo "</p>";

            }

            ?>



            <form method="post"
                  action="loginValidation.php">


                <table class="login_table">


                    <tr>

                        <td>

                            Email Address:

                        </td>

                        <td>

                            <input type="email"
                                   name="email">


                            <p class="login_error">

                                <?php

                                echo htmlspecialchars($emailError);

                                ?>

                            </p>

                        </td>

                    </tr>



                    <tr>

                        <td>

                            Password:

                        </td>

                        <td>

                            <input type="password"
                                   name="password">


                            <p class="login_error">

                                <?php

                                echo htmlspecialchars($passwordError);

                                ?>

                            </p>

                        </td>

                    </tr>


                </table>



                <div class="login_buttons">

                    <button type="submit"
                            class="login_button">

                        Login

                    </button>



                    <a href="registration.php"
                       class="signup_button">

                        Sign Up

                    </a>

                </div>


            </form>



            <a href="reset_password.php"
               class="forgot_link">

                Forgot password?

            </a>


        </div>

    </div>

</div>


</body>

</html>