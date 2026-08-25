<?php

session_start();



if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $email = $_POST["email"];

    $q1 = $_POST["q1"];

    $q2 = $_POST["q2"];

    $q3 = $_POST["q3"];

    $new_password = $_POST["new_password"];

    $confirm_password =
        $_POST["confirm_password"];



    /* Check account */

    if (!isset($_SESSION["email"])) {

        $_SESSION["resetError"] =
            "No account found. Please register first.";

        header("Location: ../View/reset_password.php");

        exit();

    }



    /* Check email */

    if ($email != $_SESSION["email"]) {

        $_SESSION["resetError"] =
            "Email is incorrect.";

        header("Location: ../View/reset_password.php");

        exit();

    }



    /* Check first answer */

    if ($q1 != $_SESSION["q1"]) {

        $_SESSION["resetError"] =
            "Favorite movie answer is incorrect.";

        header("Location: ../View/reset_password.php");

        exit();

    }



    /* Check second answer */

    if ($q2 != $_SESSION["q2"]) {

        $_SESSION["resetError"] =
            "Favorite sports team answer is incorrect.";

        header("Location: ../View/reset_password.php");

        exit();

    }

    



    /* Check third answer */

    if ($q3 != $_SESSION["q3"]) {

        $_SESSION["resetError"] =
            "Childhood hero answer is incorrect.";

        header("Location: ../View/reset_password.php");

        exit();

    }



    /* Check new password */

    if ($new_password != $confirm_password) {

        $_SESSION["resetError"] =
            "Passwords do not match.";

        header("Location: ../View/reset_password.php");

        exit();

    }



    /* Change password */

    $_SESSION["password"] =
        $new_password;



    /* Go to login */

    header("Location: ../View/login.php");

    exit();

}

?>