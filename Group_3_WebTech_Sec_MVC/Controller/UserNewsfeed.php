<?php

session_start();

include "../Model/DatabaseConnection.php";




if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../View/login.php");
    exit();
}


if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Citizen"
) {
    header("Location: ../View/login.php");
    exit();
}


$userName = $_SESSION["userName"];




$search = trim(
    $_GET["search"] ?? ""
);




$database = new DatabaseConnection();

$connection = $database->openConnection();


$result = $database->getAllPosts(
    $connection
);


$posts = [];


while ($row = $result->fetch_assoc()) {


    /* Newsfeed only shows approved posts */

    if ($row["status"] != "Approved") {
        continue;
    }


    /* Search */

    if ($search != "") {

        $searchText =
            $row["title"] . " " .
            $row["description"] . " " .
            $row["division"] . " " .
            $row["zila"] . " " .
            $row["upazila"] . " " .
            $row["union_name"] . " " .
            $row["area"];


      

        if ($row["anonymous"] == 0) {

            $searchText .=
                " " . $row["fullname"];

        }


        if (
            stripos(
                $searchText,
                $search
            ) === false
        ) {
            continue;
        }

    }


    $posts[] = $row;

}


$connection->close();


$postCount = count($posts);
