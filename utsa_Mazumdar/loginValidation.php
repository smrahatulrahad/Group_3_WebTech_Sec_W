<?php

session_start();
 





if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = $_POST["email"];

    $password = $_POST["password"];



    /* Check email */

    if ($email == "") {

        $_SESSION["emailError"] =
            "Please enter your email.";

        header("Location: ../View/login.php");

        exit();

    }



    /* Check password */

    if ($password == "") {

        $_SESSION["passwordError"] =
            "Please enter your password.";

        header("Location: ../View/login.php");

        exit();

    }



    /* Check whether user registered */

    if (!isset($_SESSION["email"])) {

        $_SESSION["loginError"] =
            "No account found. Please register first.";

        header("Location: ../View/login.php");

        exit();

    }



    /* Compare email */

    if ($email != $_SESSION["email"]) {

        $_SESSION["loginError"] =
            "Email is incorrect.";

        header("Location: ../View/login.php");

        exit();

    }



    /* Compare password */

    if ($password != $_SESSION["password"]) {

        $_SESSION["loginError"] =
            "Password is incorrect.";

        header("Location: ../View/login.php");

        exit();

    }



    /* Login successful */

    $_SESSION["loggedIn"] = true;



    ?>

    <!DOCTYPE html>
    <html>

    <head>

        <title>CiviLens - Login Successful</title>

        <link rel="stylesheet"
              href="../View/style.css">

    </head>

    <body>

        <div class="login_window">

            <div class="login_header">

                CiviLens - Login Successful

            </div>

            <div class="login_content">

                <h1>
                    Welcome
                    <?php
                    echo $_SESSION["fullname"];
                    ?>
                </h1>

                <p>

                    Account Type:
                    <?php
                    echo $_SESSION["role"];
                    ?>

                </p>

            </div>

        </div>

    </body>

    </html>

    <?php

}

?>