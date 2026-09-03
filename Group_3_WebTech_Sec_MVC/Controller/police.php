<?php

session_start();

include "../Model/DatabaseConnection.php";


/* =========================
   LOGIN CHECK
========================= */

if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../View/login.php");
    exit();
}


/* =========================
   ROLE CHECK
========================= */

if (
    !isset($_SESSION["userRole"]) ||
    $_SESSION["userRole"] != "Police"
) {
    header("Location: ../View/login.php");
    exit();
}


/* =========================
   DATABASE
========================= */

$database = new DatabaseConnection();
$connection = $database->openConnection();


/* =========================
   CURRENT POLICE USER
========================= */

$policeResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if (
    !$policeResult ||
    $policeResult->num_rows == 0
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}


$policeUser = $policeResult->fetch_assoc();


if (
    $policeUser["role"] != "Police" ||
    $policeUser["status"] != "Active"
) {
    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../View/login.php");
    exit();
}


$policeId = (int)$policeUser["id"];
$policeName = $policeUser["fullname"];


/* Update login session */

$_SESSION["userName"] = $policeUser["fullname"];
$_SESSION["userEmail"] = $policeUser["email"];
$_SESSION["userRole"] = $policeUser["role"];


/* =========================
   CASE ACTION
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $postId = (int)($_POST["post_id"] ?? 0);
    $action = $_POST["action"] ?? "";


    if ($postId > 0) {

        /* Check that post exists and is Approved */

        $postIsApproved =
            $database->isPostInStatus(
                $connection,
                $postId,
                "Approved"
            );


        if ($postIsApproved) {

            /* Get current police case */

            $caseResult =
                $database->getPoliceCaseByPost(
                    $connection,
                    $postId
                );

            $currentCase = null;


            if ($caseResult->num_rows > 0) {
                $currentCase =
                    $caseResult->fetch_assoc();
            }


            /* =========================
               TAKE
            ========================= */

            if ($action == "take") {

                /*
                    A Police officer can take an
                    unassigned case.

                    If another Police officer already
                    has it, this request is ignored.
                */

                if (
                    $currentCase == null ||
                    $currentCase["assigned_police_id"] == null
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "In Progress"
                    );
                }
            }


            /* =========================
               RELEASE
            ========================= */

            elseif ($action == "release") {

                if (
                    $currentCase != null &&
                    $currentCase["assigned_police_id"] != null &&
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        null,
                        "Open"
                    );
                }
            }


            /* =========================
               RESOLVE
            ========================= */

            elseif ($action == "resolve") {

                /*
                    Resolve if:
                    - no case record exists yet, or
                    - case is unassigned, or
                    - logged-in Police owns it.
                */

                if (
                    $currentCase == null ||
                    $currentCase["assigned_police_id"] == null ||
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "Resolved"
                    );
                }
            }


            /* =========================
               UNMARK RESOLVED
            ========================= */

            elseif ($action == "unresolve") {

                if (
                    $currentCase != null &&
                    $currentCase["status"] == "Resolved" &&
                    $currentCase["assigned_police_id"] != null &&
                    (int)$currentCase["assigned_police_id"] == $policeId
                ) {
                    $database->savePoliceCase(
                        $connection,
                        $postId,
                        $policeId,
                        "In Progress"
                    );
                }
            }
        }
    }


    $connection->close();

    header("Location: ../View/police.php");
    exit();
}


/* =========================
   GET POSTS
========================= */

$postsResult =
    $database->getAllPosts(
        $connection
    );


/* =========================
   POST INFORMATION FOR VIEW
========================= */

$policePostInfo = array();

if ($postsResult && $postsResult->num_rows > 0) {

    while ($postData = $postsResult->fetch_assoc()) {

        if ($postData["status"] != "Approved") {
            continue;
        }

        $citizenName = "Anonymous User";

        if ((int)$postData["anonymous"] == 0) {

            $citizenResult = $database->getUserById(
                $connection,
                $postData["user_id"]
            );

            if ($citizenResult && $citizenResult->num_rows > 0) {
                $citizen = $citizenResult->fetch_assoc();
                $citizenName = $citizen["fullname"];
            }
        }

        $caseStatus = "Open";
        $assignedPoliceId = null;
        $takenBy = "";

        $caseResult = $database->getPoliceCaseByPost(
            $connection,
            $postData["id"]
        );

        if ($caseResult->num_rows > 0) {

            $case = $caseResult->fetch_assoc();
            $caseStatus = $case["status"];
            $assignedPoliceId = $case["assigned_police_id"];

            if ($assignedPoliceId != null) {

                if ((int)$assignedPoliceId == $policeId) {
                    $takenBy = "me";
                }
                else {
                    $takenBy = "other";
                }
            }
        }

        $policePostInfo[(int)$postData["id"]] = array(
            "citizenName" => $citizenName,
            "caseStatus" => $caseStatus,
            "assignedPoliceId" => $assignedPoliceId,
            "takenBy" => $takenBy
        );
    }

    $postsResult->data_seek(0);
}
