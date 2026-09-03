<?php

session_start();

include "../Model/DatabaseConnection.php";


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: ../View/registration.php");
    exit();

}


/* Common information */

$role = $_POST["role"] ?? "";

$fullname = trim($_POST["fullname"] ?? "");

$email = strtolower(
    trim($_POST["email"] ?? "")
);

$password = $_POST["password"] ?? "";

$phone = trim($_POST["phone"] ?? "");

$q1 = strtolower(
    trim($_POST["q1"] ?? "")
);

$q2 = strtolower(
    trim($_POST["q2"] ?? "")
);

$q3 = strtolower(
    trim($_POST["q3"] ?? "")
);


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

    header("Location: ../View/registration.php");
    exit();

}


/* Check email */

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

    $_SESSION["registrationError"] =
        "Enter a valid email address.";

    header("Location: ../View/registration.php");
    exit();

}


/* Check password */

if (strlen($password) < 6) {

    $_SESSION["registrationError"] =
        "Password must be at least 6 characters.";

    header("Location: ../View/registration.php");
    exit();

}


/* Check phone */

if (!ctype_digit($phone)) {

    $_SESSION["registrationError"] =
        "Phone number must contain numbers only.";

    header("Location: ../View/registration.php");
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

    header("Location: ../View/registration.php");
    exit();

}


/* Default role-specific values */

$district = null;
$upazila = null;
$union = null;
$area = null;
$nid = null;

$policeRank = null;
$station = null;
$badge = null;

$channel = null;
$journalistId = null;


/* Citizen information */

if ($role == "citizen") {

    $roleName = "Citizen";

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

        header("Location: ../View/registration.php");
        exit();

    }

}


/* Police information */

elseif ($role == "police") {

    $roleName = "Police";

    $policeRank =
        trim($_POST["rank"] ?? "");

    $station =
        trim($_POST["station"] ?? "");

    $badge =
        trim($_POST["badge"] ?? "");


    if (
        $policeRank == "" ||
        $station == "" ||
        $badge == ""
    ) {

        $_SESSION["registrationError"] =
            "Please complete all Police information.";

        header("Location: ../View/registration.php");
        exit();

    }

}


/* Journalist information */

else {

    $roleName = "Journalist";

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

        header("Location: ../View/registration.php");
        exit();

    }

}


/* Open database connection */

$database = new DatabaseConnection();

$connection = $database->openConnection();


/* Check duplicate email */

$result = $database->getUserByEmail(
    $connection,
    $email
);


if ($result->num_rows > 0) {

    $_SESSION["registrationError"] =
        "An account with this email already exists.";

    $connection->close();

    header("Location: ../View/registration.php");
    exit();

}


/* Hash password */

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);


/* Register user */

$registered = $database->registerUser(
    $connection,
    $fullname,
    $email,
    $hashedPassword,
    $phone,
    $roleName,
    $district,
    $upazila,
    $union,
    $area,
    $nid,
    $policeRank,
    $station,
    $badge,
    $channel,
    $journalistId,
    $q1,
    $q2,
    $q3
);


/* Check registration result */

if (!$registered) {

    $_SESSION["registrationError"] =
        "Registration failed. Please try again.";

    $connection->close();

    header("Location: ../View/registration.php");
    exit();

}


$connection->close();


/* Registration successful */

header("Location: ../View/login.php");
exit();

?>