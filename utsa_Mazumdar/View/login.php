<?php
session_start();

$emailError = isset($_SESSION["emailError"]) ? $_SESSION["emailError"] : "";
$passwordError = isset($_SESSION["passwordError"]) ? $_SESSION["passwordError"] : "";
$loginFailMessage = isset($_SESSION["loginFailMessage"]) ? $_SESSION["loginFailMessage"] : "";

unset($_SESSION["emailError"]);
unset($_SESSION["passwordError"]);
unset($_SESSION["loginFailMessage"]);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CiviLens - Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="login_window">

        <div class="login_header">
            CiviLens - Sign In
        </div>

        <div class="login_content">
            <h1 id="login_title">
                WELCOME TO CIVILENS
            </h1>

            <p id="login_subtitle">
                Multimedia Complaint Management Tracking System
            </p>

            <div class="login_form_box">

                <?php
                if ($loginFailMessage != "") {
                    echo "<p class='login_error'>$loginFailMessage</p>";
                }
                ?>

                <form method="post" action="../Controller/loginValidation.php">

                    <table class="login_table">
                        <tr>
                            <td>Email Address:</td>
                            <td>
                                <input type="email" name="email">

                                <p class="login_error">
                                    <?php echo $emailError; ?>
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <td>Password:</td>
                            <td>
                                <input type="password" name="password">

                                <p class="login_error">
                                    <?php echo $passwordError; ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <div class="login_buttons">
                        <button type="submit" class="login_button">
                            Login
                        </button>

                        <a href="registration.php" class="signup_button">
                            Sign Up
                        </a>
                    </div>
                </form>

                <a href="reset_password.php" class="forgot_link">
                    Forgot password?
                </a>
            </div>
        </div>
    </div>
</body>
</html>