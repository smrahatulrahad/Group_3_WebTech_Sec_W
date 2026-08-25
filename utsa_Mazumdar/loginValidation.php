<?php

session_start();


if ($_SERVER["REQUEST_METHOD"] != "POST") {

    header("Location: login.php");
    exit();

}


$email = strtolower(trim($_POST["email"] ?? ""));
$password = $_POST["password"] ?? "";


/* Check email */

if ($email == "") {

    $_SESSION["emailError"] =
        "Please enter your email.";

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


/*
    Demo accounts.
    Database will replace these later.
*/

$demoUsers = [

    "citizen@civiclens.com" => [

        "password" => "123456",
        "name" => "Nafis Rahman",
        "role" => "Citizen"

    ],


    "journalist@civiclens.com" => [

        "password" => "123456",
        "name" => "Samia Karim",
        "role" => "Journalist"

    ],


    "police@civiclens.com" => [

        "password" => "123456",
        "name" => "Tanvir Hasan",
        "role" => "Police"

    ],


    "admin@civiclens.com" => [

        "password" => "123456",
        "name" => "Mahmud Rahman",
        "role" => "Admin"

    ],


    "moderator@civiclens.com" => [

        "password" => "123456",
        "name" => "Farhan Kabir",
        "role" => "Moderator"

    ]

];


$userFound = false;


/* Check demo accounts */

if (isset($demoUsers[$email])) {

    if ($demoUsers[$email]["password"] == $password) {

        $_SESSION["userName"] =
            $demoUsers[$email]["name"];

        $_SESSION["userRole"] =
            $demoUsers[$email]["role"];

        $_SESSION["userEmail"] =
            $email;

        $_SESSION["userPhone"] = "";

        $userFound = true;

    }

}


/* Check newly registered accounts */

if (
    $userFound == false &&
    isset($_SESSION["registeredUsers"][$email])
) {

    $registeredUser =
        $_SESSION["registeredUsers"][$email];


    if (
        password_verify(
            $password,
            $registeredUser["password"]
        )
    ) {

        $_SESSION["userName"] =
            $registeredUser["name"];

        $_SESSION["userRole"] =
            $registeredUser["role"];

        $_SESSION["userEmail"] =
            $registeredUser["email"];

        $_SESSION["userPhone"] =
            $registeredUser["phone"] ?? "";


        /* Citizen information */

        if ($registeredUser["role"] == "Citizen") {

            $_SESSION["district"] =
                $registeredUser["district"] ?? "";

            $_SESSION["upazila"] =
                $registeredUser["upazila"] ?? "";

            $_SESSION["address"] =
                ($registeredUser["area"] ?? "") .
                ", " .
                ($registeredUser["union"] ?? "");

            $_SESSION["nid"] =
                $registeredUser["nid"] ?? "";

        }


        /* Police information */

        if ($registeredUser["role"] == "Police") {

            $_SESSION["rank"] =
                $registeredUser["rank"] ?? "";

            $_SESSION["stationName"] =
                $registeredUser["station"] ?? "";

            $_SESSION["badgeNumber"] =
                $registeredUser["badge"] ?? "";

        }


        /* Journalist information */

        if ($registeredUser["role"] == "Journalist") {

            $_SESSION["channelName"] =
                $registeredUser["channel"] ?? "";

            $_SESSION["journalistId"] =
                $registeredUser["journalistId"] ?? "";

        }


        $userFound = true;

    }

}


/* Invalid login */

if ($userFound == false) {

    $_SESSION["loginError"] =
        "Invalid email address or password.";

    header("Location: login.php");
    exit();

}


/* Login successful */

session_regenerate_id(true);

$_SESSION["loggedIn"] = true;


/* Redirect according to role */

if ($_SESSION["userRole"] == "Citizen") {

    header(
        "Location: ../S.M. Rahatul Islam/UserNewsfeed.php"
    );

    exit();

}


if ($_SESSION["userRole"] == "Journalist") {

    header(
        "Location: ../Adnan Raad/journalist.php"
    );

    exit();

}


if ($_SESSION["userRole"] == "Police") {

    header(
        "Location: ../Adnan Raad/police.php"
    );

    exit();

}


if (
    $_SESSION["userRole"] == "Admin" ||
    $_SESSION["userRole"] == "Moderator"
) {

    header(
        "Location: ../Adnan Raad/AdminNewsfeed.php"
    );

    exit();

}


$_SESSION["loginError"] =
    "User role is not recognized.";

header("Location: login.php");
exit();

?>