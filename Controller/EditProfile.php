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


$userId = (int) $_SESSION["userId"];
$errorMessage = "";




$database = new DatabaseConnection();

$connection = $database->openConnection();



   //GET CURRENT USER
  

$result = $database->getUserById(
    $connection,
    $userId
);


if ($result->num_rows == 0) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}


$user = $result->fetch_assoc();


if ($user["status"] == "Disabled") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();
}


$userRole = $user["role"];


// This profile page is only for these roles 

if (
    $userRole != "Citizen" &&
    $userRole != "Police" &&
    $userRole != "Journalist"
) {

    $connection->close();

    header("Location: AdminNewsfeed.php");
    exit();
}




if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $userName = trim(
        $_POST["userName"] ?? ""
    );

    $userPhone = trim(
        $_POST["userPhone"] ?? ""
    );


    if (
        $userName == "" ||
        $userPhone == ""
    ) {

        $errorMessage =
            "Please complete all required fields.";

    }

    elseif (!ctype_digit($userPhone)) {

        $errorMessage =
            "Phone number must contain numbers only.";

    }

    else {


       
          // CITIZEN
           

        if ($userRole == "Citizen") {

            $address = trim(
                $_POST["address"] ?? ""
            );

            $district = trim(
                $_POST["district"] ?? ""
            );

            $upazila = trim(
                $_POST["upazila"] ?? ""
            );

            $nid = trim(
                $_POST["nid"] ?? ""
            );


            if (
                $address == "" ||
                $district == "" ||
                $upazila == "" ||
                $nid == ""
            ) {

                $errorMessage =
                    "Please complete all Citizen information.";

            }

            else {

                $profileUpdated =
                    $database->updateCitizenProfile(
                        $connection,
                        $userId,
                        $userName,
                        $userPhone,
                        $address,
                        $district,
                        $upazila,
                        $nid
                    );

            }

        }


        
           //JOURNALIST
           

        elseif ($userRole == "Journalist") {

            $journalistId = trim(
                $_POST["journalistId"] ?? ""
            );

            $channelName = trim(
                $_POST["channelName"] ?? ""
            );


            if (
                $journalistId == "" ||
                $channelName == ""
            ) {

                $errorMessage =
                    "Please complete all Journalist information.";

            }

            else {

                $profileUpdated =
                    $database->updateJournalistProfile(
                        $connection,
                        $userId,
                        $userName,
                        $userPhone,
                        $journalistId,
                        $channelName
                    );

            }

        }


        
          // POLICE
           

        else {

            $badgeNumber = trim(
                $_POST["badgeNumber"] ?? ""
            );

            $rank = trim(
                $_POST["rank"] ?? ""
            );

            $stationName = trim(
                $_POST["stationName"] ?? ""
            );


            if (
                $badgeNumber == "" ||
                $rank == "" ||
                $stationName == ""
            ) {

                $errorMessage =
                    "Please complete all Police information.";

            }

            else {

                $profileUpdated =
                    $database->updatePoliceProfile(
                        $connection,
                        $userId,
                        $userName,
                        $userPhone,
                        $badgeNumber,
                        $rank,
                        $stationName
                    );

            }

        }


       

        if (
            $errorMessage == "" &&
            isset($profileUpdated)
        ) {

            if ($profileUpdated) {

                $_SESSION["userName"] =
                    $userName;

                $connection->close();

                header("Location: Profile.php");
                exit();

            }

            else {

                $errorMessage =
                    "Profile could not be updated. Please try again.";

            }

        }

    }

}



  // GET LATEST USER INFORMATION
  

$result = $database->getUserById(
    $connection,
    $userId
);

$user = $result->fetch_assoc();


$userName = $user["fullname"];
$userEmail = $user["email"];
$userPhone = $user["phone"];


/* Citizen information */

$address = $user["address"] ?? "";
$district = $user["district"] ?? "";
$upazila = $user["upazila"] ?? "";
$nid = $user["nid"] ?? "";


// Journalist information 

$journalistId =
    $user["journalist_id"] ?? "";

$channelName =
    $user["channel_name"] ?? "";


// Police information 

$badgeNumber =
    $user["badge_number"] ?? "";

$rank =
    $user["police_rank"] ?? "";

$stationName =
    $user["station_name"] ?? "";


// Keep normal login session updated 

$_SESSION["userName"] = $userName;
$_SESSION["userRole"] = $userRole;
$_SESSION["userEmail"] = $userEmail;


$connection->close();



 //  CORRECT NEWSFEED
  

$newsfeedPage = "UserNewsfeed.php";


if ($userRole == "Journalist") {

    $newsfeedPage =
        "journalist.php";

}


elseif ($userRole == "Police") {

    $newsfeedPage =
        "police.php";

}
