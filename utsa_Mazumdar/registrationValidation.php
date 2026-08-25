<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: registration.php");
    exit();

}


$role = $_POST["role"] ?? "";
$fullname = trim($_POST["fullname"] ?? "");
$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";
$phone = trim($_POST["phone"] ?? "");

$q1 = strtolower(trim($_POST["q1"] ?? ""));
$q2 = strtolower(trim($_POST["q2"] ?? ""));
$q3 = strtolower(trim($_POST["q3"] ?? ""));


/* Check common information */

if (
    $fullname == "" ||
    $email == "" ||
    $password == "" ||
    $phone == "" ||
    $q1 == "" ||
    $q2 == "" ||
    $q3 == ""
) {

    $_SESSION["registrationError"] =
        "Please complete all required fields.";

    header("Location: registration.php");
    exit();

}


/* Check email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["registrationError"] =
        "Enter a valid email address.";

    header("Location: registration.php");
    exit();

}


/* Check password */

if (strlen($password) < 6) {

    $_SESSION["registrationError"] =
        "Password must be at least 6 characters.";

    header("Location: registration.php");
    exit();

}


/* Check phone */

if (!ctype_digit($phone)) {

    $_SESSION["registrationError"] =
        "Phone number must contain numbers only.";

    header("Location: registration.php");
    exit();

}


/* Check role */

if (
    $role != "citizen" &&
    $role != "police" &&
    $role != "journalist"
) {

    $_SESSION["registrationError"] =
        "Please select a valid account type.";

    header("Location: registration.php");
    exit();

}


/* Create registered users list */

if (!isset($_SESSION["registeredUsers"])) {

    $_SESSION["registeredUsers"] = [];

}


/* Check duplicate account */

if (isset($_SESSION["registeredUsers"][$email])) {

    $_SESSION["registrationError"] =
        "An account with this email already exists.";

    header("Location: registration.php");
    exit();

}


/* Convert role name */

if ($role == "citizen") {

    $roleName = "Citizen";

} elseif ($role == "police") {

    $roleName = "Police";

} else {

    $roleName = "Journalist";

}


/* Common account information */

$newUser = [

    "name" => $fullname,
    "email" => $email,
    "password" => password_hash(
        $password,
        PASSWORD_DEFAULT
    ),
    "phone" => $phone,
    "role" => $roleName,
    "q1" => $q1,
    "q2" => $q2,
    "q3" => $q3

];


/* Citizen information */

if ($role == "citizen") {

    $district =
        trim($_POST["district"] ?? "");

    $upazila =
        trim($_POST["upazila"] ?? "");

    $union =
        trim($_POST["union"] ?? "");

    $area =
        trim($_POST["area"] ?? "");

    $nid =
        trim($_POST["nid"] ?? "");


    if (
        $district == "" ||
        $upazila == "" ||
        $union == "" ||
        $area == "" ||
        $nid == ""
    ) {

        $_SESSION["registrationError"] =
            "Please complete all Citizen information.";

        header("Location: registration.php");
        exit();

    }


    $newUser["district"] = $district;
    $newUser["upazila"] = $upazila;
    $newUser["union"] = $union;
    $newUser["area"] = $area;
    $newUser["nid"] = $nid;

}


/* Police information */

if ($role == "police") {

    $rank =
        trim($_POST["rank"] ?? "");

    $station =
        trim($_POST["station"] ?? "");

    $badge =
        trim($_POST["badge"] ?? "");


    if (
        $rank == "" ||
        $station == "" ||
        $badge == ""
    ) {

        $_SESSION["registrationError"] =
            "Please complete all Police information.";

        header("Location: registration.php");
        exit();

    }


    $newUser["rank"] = $rank;
    $newUser["station"] = $station;
    $newUser["badge"] = $badge;

}


/* Journalist information */

if ($role == "journalist") {

    $channel =
        trim($_POST["channel"] ?? "");

    $journalistId =
        trim($_POST["journalist_id"] ?? "");


    if (
        $channel == "" ||
        $journalistId == ""
    ) {

        $_SESSION["registrationError"] =
            "Please complete all Journalist information.";

        header("Location: registration.php");
        exit();

    }


    $newUser["channel"] = $channel;
    $newUser["journalistId"] = $journalistId;

}


/* Save account */

$_SESSION["registeredUsers"][$email] = $newUser;


/* Go to login page */

header("Location: login.php");
exit();

?>