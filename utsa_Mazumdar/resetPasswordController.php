<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: reset_password.php");
    exit();

}


$email = trim($_POST["email"] ?? "");

$q1 = strtolower(trim($_POST["q1"] ?? ""));

$q2 = strtolower(trim($_POST["q2"] ?? ""));

$q3 = strtolower(trim($_POST["q3"] ?? ""));

$newPassword = $_POST["new_password"] ?? "";

$confirmPassword = $_POST["confirm_password"] ?? "";



/* Check empty fields */

if (
    $email == "" ||
    $q1 == "" ||
    $q2 == "" ||
    $q3 == "" ||
    $newPassword == "" ||
    $confirmPassword == ""
) {

    $_SESSION["resetError"] =
        "Please complete all fields.";

    header("Location: reset_password.php");

    exit();

}



/* Check email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["resetError"] =
        "Enter a valid email address.";

    header("Location: reset_password.php");

    exit();

}



/* Check account */

if (
    !isset($_SESSION["registeredUsers"]) ||
    !isset($_SESSION["registeredUsers"][$email])
) {

    $_SESSION["resetError"] =
        "No registered account was found with this email address.";

    header("Location: reset_password.php");

    exit();

}



$user =
    $_SESSION["registeredUsers"][$email];



/* Check security answers */

if (
    $user["q1"] != $q1 ||
    $user["q2"] != $q2 ||
    $user["q3"] != $q3
) {

    $_SESSION["resetError"] =
        "Security question answers do not match.";

    header("Location: reset_password.php");

    exit();

}



/* Check password length */

if (strlen($newPassword) < 6) {

    $_SESSION["resetError"] =
        "Password must be at least 6 characters.";

    header("Location: reset_password.php");

    exit();

}



/* Check password confirmation */

if ($newPassword != $confirmPassword) {

    $_SESSION["resetError"] =
        "New password and confirm password do not match.";

    header("Location: reset_password.php");

    exit();

}



/* Update password */

$_SESSION["registeredUsers"][$email]["password"] =
    password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );



$_SESSION["resetSuccess"] =
    "Password changed successfully. You can now sign in.";


header("Location: reset_password.php");

exit();

?>