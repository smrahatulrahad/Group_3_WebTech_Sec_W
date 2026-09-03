<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: login.php");
    exit();
}


if (!isset($_SESSION["userId"])) {

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}

// DATABASE
   

$database = new DatabaseConnection();

$connection = $database->openConnection();


$result = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if ($result->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}


$user = $result->fetch_assoc();


// Disabled user should not continue 

if ($user["status"] == "Disabled") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}



   //USER INFORMATION
   

$userName = $user["fullname"];
$userRole = $user["role"];
$userEmail = $user["email"];
$userPhone = $user["phone"] ?? "";


// Citizen information 

$address = $user["address"] ?? "";
$district = $user["district"] ?? "";
$upazila = $user["upazila"] ?? "";
$nid = $user["nid"] ?? "";


// Journalist information 

$journalistId = $user["journalist_id"] ?? "";
$channelName = $user["channel_name"] ?? "";


// Police information 

$badgeNumber = $user["badge_number"] ?? "";
$rank = $user["police_rank"] ?? "";
$stationName = $user["station_name"] ?? "";


// Keep basic session information updated 

$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $userEmail;


$connection->close();




if (
    $userRole != "Citizen" &&
    $userRole != "Police" &&
    $userRole != "Journalist"
) {

    header("Location: AdminNewsfeed.php");
    exit();
}


/* Choose correct newsfeed */

$newsfeedPage = "UserNewsfeed.php";


if ($userRole == "Journalist") {

    $newsfeedPage =
        "journalist.php";

}


elseif ($userRole == "Police") {

    $newsfeedPage =
        "police.php";

}
