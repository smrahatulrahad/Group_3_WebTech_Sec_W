<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true ||
    !isset($_SESSION["userId"])
) {
    header("Location: login.php");
    exit();
}


$database = new DatabaseConnection();

$connection = $database->openConnection();



$userResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if ($userResult->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}


$user = $userResult->fetch_assoc();


if ($user["status"] == "Disabled") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}


$userName = $user["fullname"];
$userRole = $user["role"];


// Only valid project roles 

if (
    $userRole != "Citizen" &&
    $userRole != "Police" &&
    $userRole != "Journalist" &&
    $userRole != "Admin" &&
    $userRole != "Moderator"
) {

    $connection->close();

    header("Location: login.php");
    exit();
}


// login session information updated 

$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $user["email"];



   //CORRECT NEWSFEED
   

$newsfeedPage = "UserNewsfeed.php";


if ($userRole == "Journalist") {

    $newsfeedPage =
        "journalist.php";

}


elseif ($userRole == "Police") {

    $newsfeedPage =
        "police.php";

}


elseif (
    $userRole == "Admin" ||
    $userRole == "Moderator"
) {

    $newsfeedPage =
        "AdminNewsfeed.php";

}



$result = $database->getShowCasePosts(
    $connection
);

$cases = [];


while ($row = $result->fetch_assoc()) {


    /* Police case status has priority */

    if ($row["police_status"] != null) {

        $caseStatus =
            $row["police_status"];

    }


    /* Journalist is covering the post */

    elseif (
        (int) $row["coverage_count"] > 0
    ) {

        $caseStatus =
            "Covered";

    }


    /* Approved but not taken yet */

    else {

        $caseStatus =
            "Open";

    }


    $row["case_status"] =
        $caseStatus;


    $cases[] = $row;

}


$connection->close();


$caseCount = count($cases);
