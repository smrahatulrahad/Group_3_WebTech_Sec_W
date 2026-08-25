<?php

session_start();

include "../Model/DatabaseConnection.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: login.php");
    exit();

}


$email = strtolower(
    trim($_POST["email"] ?? "")
);

$password = $_POST["password"] ?? "";


/* Check email */

if ($email == "") {

    $_SESSION["emailError"] =
        "Please enter your email.";

    header("Location: login.php");
    exit();

}


if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["emailError"] =
        "Please enter a valid email address.";

    header("Location: login.php");
    exit();

}


/* Check password */

if ($password == "") {

    $_SESSION["passwordError"] =
        "Please enter your password.";

    header("Location: login.php");
    exit();

}


/* Open database connection */

$database = new DatabaseConnection();

$connection = $database->openConnection();


/* Find user by email */

$result = $database->getUserByEmail(
    $connection,
    $email
);


if ($result->num_rows == 0) {

    $_SESSION["loginError"] =
        "Invalid email address or password.";

    $connection->close();

    header("Location: login.php");
    exit();

}


$user = $result->fetch_assoc();


/* Check account status */

if ($user["status"] == "Disabled") {

    $_SESSION["loginError"] =
        "Your account is disabled.";

    $connection->close();

    header("Location: login.php");
    exit();

}


/* Check password */

if (
    !password_verify(
        $password,
        $user["password"]
    )
) {

    $_SESSION["loginError"] =
        "Invalid email address or password.";

    $connection->close();

    header("Location: login.php");
    exit();

}


/* Find redirect according to role */

$redirectPage = "";


if ($user["role"] == "Citizen") {

    $redirectPage =
        "../S.M. Rahatul Islam/UserNewsfeed.php";

}


elseif ($user["role"] == "Journalist") {

    $redirectPage =
        "../Adnan Raad/journalist.php";

}


elseif ($user["role"] == "Police") {

    $redirectPage =
        "../Adnan Raad/police.php";

}


elseif (
    $user["role"] == "Admin" ||
    $user["role"] == "Moderator"
) {

    $redirectPage =
        "../Adnan Raad/AdminNewsfeed.php";

}


else {

    $_SESSION["loginError"] =
        "User role is not recognized.";

    $connection->close();

    header("Location: login.php");
    exit();

}


/* Login successful */

session_regenerate_id(true);

$_SESSION["loggedIn"] = true;

$_SESSION["userId"] =
    $user["id"];

$_SESSION["userName"] =
    $user["fullname"];

$_SESSION["userRole"] =
    $user["role"];

$_SESSION["userEmail"] =
    $user["email"];


$connection->close();


/* Redirect */

header("Location: " . $redirectPage);
exit();

?>