<?php

session_start();

include "../Model/DatabaseConnection.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: ../View/reset_password.php");
    exit();

}


/* Get form data */

$email = strtolower(
    trim($_POST["email"] ?? "")
);

$q1 = strtolower(
    trim($_POST["q1"] ?? "")
);

$q2 = strtolower(
    trim($_POST["q2"] ?? "")
);

$q3 = strtolower(
    trim($_POST["q3"] ?? "")
);

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

    header("Location: ../View/reset_password.php");
    exit();

}



/* Check email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["resetError"] =
        "Enter a valid email address.";

    header("Location: ../View/reset_password.php");
    exit();

}



/* Check password length */

if (strlen($newPassword) < 6) {

    $_SESSION["resetError"] =
        "Password must be at least 6 characters.";

    header("Location: ../View/reset_password.php");
    exit();

}



/* Check password confirmation */

if ($newPassword != $confirmPassword) {

    $_SESSION["resetError"] =
        "New password and confirm password do not match.";

    header("Location: ../View/reset_password.php");
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

    $_SESSION["resetError"] =
        "No registered account was found with this email address.";

    $connection->close();

    header("Location: ../View/reset_password.php");
    exit();

}


$user = $result->fetch_assoc();



/* Get stored security answers */

$storedAnswer1 = strtolower(
    trim($user["security_answer1"] ?? "")
);

$storedAnswer2 = strtolower(
    trim($user["security_answer2"] ?? "")
);

$storedAnswer3 = strtolower(
    trim($user["security_answer3"] ?? "")
);



/* Check security answers */

if (
    $storedAnswer1 != $q1 ||
    $storedAnswer2 != $q2 ||
    $storedAnswer3 != $q3
) {

    $_SESSION["resetError"] =
        "Security question answers do not match.";

    $connection->close();

    header("Location: ../View/reset_password.php");
    exit();

}



/* Hash new password */

$hashedPassword = password_hash(
    $newPassword,
    PASSWORD_DEFAULT
);



/* Update password using existing DatabaseConnection */

$updated = $database->updatePassword(
    $connection,
    $email,
    $hashedPassword
);



/* Close connection */

$connection->close();



/* Check update result */

if (!$updated) {

    $_SESSION["resetError"] =
        "Password could not be changed. Please try again.";

    header("Location: ../View/reset_password.php");
    exit();

}



/* Success */

$_SESSION["resetSuccess"] =
    "Password changed successfully. You can now sign in.";


header("Location: ../View/reset_password.php");
exit();

?>