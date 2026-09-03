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

    header("Location: login.php");
    exit();

}


/* =========================
   ROLE CHECK
========================= */

if (
    !isset($_SESSION["userRole"]) ||
    (
        $_SESSION["userRole"] != "Admin" &&
        $_SESSION["userRole"] != "Moderator"
    )
) {

    header("Location: login.php");
    exit();

}


/* =========================
   DATABASE
========================= */

$database = new DatabaseConnection();

$connection = $database->openConnection();


/* =========================
   CURRENT USER
========================= */

$currentUserResult = $database->getUserById(
    $connection,
    $_SESSION["userId"]
);


if (
    !$currentUserResult ||
    $currentUserResult->num_rows == 0
) {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}


$currentUser = $currentUserResult->fetch_assoc();


/* Disabled account cannot continue */

if ($currentUser["status"] != "Active") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: login.php");
    exit();

}


$userName = $currentUser["fullname"];
$userRole = $currentUser["role"];


/* Update login session information */

$_SESSION["userName"] = $userName;
$_SESSION["userEmail"] = $currentUser["email"];
$_SESSION["userRole"] = $userRole;


/* =========================
   MESSAGE
========================= */

$message = "";


/* =========================
   APPLY CHANGES
========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedUsers =
        $_POST["selectedUsers"] ?? array();

    $actions =
        $_POST["actions"] ?? array();


    if (count($selectedUsers) == 0) {

        $message =
            "Please select at least one user.";

    }
    else {

        $changedCount = 0;

        $notAllowedCount = 0;


        foreach ($selectedUsers as $selectedUserId) {

            $selectedUserId =
                (int)$selectedUserId;


            if ($selectedUserId <= 0) {

                continue;

            }


            $action =
                $actions[$selectedUserId] ?? "None";


            if (
                $action != "Enable" &&
                $action != "Disable" &&
                $action != "Remove"
            ) {

                continue;

            }


            /* Get selected user */

            $selectedUserResult =
                $database->getUserById(
                    $connection,
                    $selectedUserId
                );


            if (
                !$selectedUserResult ||
                $selectedUserResult->num_rows == 0
            ) {

                continue;

            }


            $selectedUser =
                $selectedUserResult->fetch_assoc();


            /* Moderator cannot modify Admin */

            if (
                $userRole == "Moderator" &&
                $selectedUser["role"] == "Admin"
            ) {

                $notAllowedCount++;

                continue;

            }


            /* Enable */

            if ($action == "Enable") {

                $updated =
                    $database->updateUserStatus(
                        $connection,
                        $selectedUserId,
                        "Active"
                    );


                if ($updated) {

                    $changedCount++;

                }

            }


            /* Disable */

            elseif ($action == "Disable") {

                $updated =
                    $database->updateUserStatus(
                        $connection,
                        $selectedUserId,
                        "Disabled"
                    );


                if ($updated) {

                    $changedCount++;

                }

            }


            /* Remove */

            elseif ($action == "Remove") {

                $deleted =
                    $database->deleteUser(
                        $connection,
                        $selectedUserId
                    );


                if ($deleted) {

                    $changedCount++;

                }

            }

        }


        /* Result message */

        if (
            $changedCount > 0 &&
            $notAllowedCount == 0
        ) {

            $message =
                "Changes saved successfully.";

        }


        elseif (
            $changedCount > 0 &&
            $notAllowedCount > 0
        ) {

            $message =
                "Changes saved. Moderator cannot modify Admin accounts.";

        }


        elseif (
            $changedCount == 0 &&
            $notAllowedCount > 0
        ) {

            $message =
                "Moderator cannot modify Admin accounts.";

        }


        else {

            $message =
                "No changes were selected.";

        }

    }

}


/* =========================
   GET ALL USERS
========================= */

$usersResult =
    $database->getAllUsers(
        $connection
    );


$userCount = 0;


if ($usersResult) {

    $userCount =
        $usersResult->num_rows;

}
