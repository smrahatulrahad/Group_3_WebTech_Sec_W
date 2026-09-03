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
    $_SESSION["userRole"] != "Admin" &&
    $_SESSION["userRole"] != "Moderator"
) {

    header("Location: login.php");
    exit();

}


$userName = $_SESSION["userName"];
$userRole = $_SESSION["userRole"];

$message = "";


/* =========================
   DATABASE CONNECTION
   ========================= */

$database = new DatabaseConnection();

$connection = $database->openConnection();



/* =========================
   ADD / REMOVE STAFF
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";

    $staffType = $_POST["staffType"] ?? "";

    $email = strtolower(
        trim($_POST["email"] ?? "")
    );

    $password = $_POST["password"] ?? "";



    /* Check staff type */

    if (
        $staffType != "Admin" &&
        $staffType != "Moderator"
    ) {

        $message = "Invalid staff type.";

    }


    /* Only Admin can manage Admin */

    elseif (
        $staffType == "Admin" &&
        $userRole != "Admin"
    ) {

        $message =
            "Only Admin can manage Admin accounts.";

    }


    /* Check email */

    elseif ($email == "") {

        $message =
            "Please enter an email address.";

    }

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message =
            "Please enter a valid email address.";

    }


    /* =========================
       ADD STAFF
       ========================= */

    elseif ($action == "Add") {

        if ($password == "") {

            $message =
                "Please enter a password.";

        }

        elseif (strlen($password) < 6) {

            $message =
                "Password must be at least 6 characters.";

        }

        else {

            /* Check if email already exists */

            $result =
                $database->getUserByEmail(
                    $connection,
                    $email
                );


            if ($result->num_rows > 0) {

                $message =
                    "This email already has an account.";

            }

            else {

                /*
                 * Create name from email
                 */

                $name = explode("@", $email)[0];

                $name = str_replace(
                    [".", "_", "-"],
                    " ",
                    $name
                );

                $name = ucwords($name);



                /*
                 * Hash password
                 */

                $hashedPassword =
                    password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );



                /*
                 * Use existing registerUser()
                 */

                $success =
                    $database->registerUser(

                        $connection,

                        $name,

                        $email,

                        $hashedPassword,

                        "",

                        $staffType,

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        "",

                        ""

                    );


                if ($success) {

                    $message =
                        $staffType .
                        " added successfully.";

                }

                else {

                    $message =
                        "Could not add " .
                        $staffType .
                        ".";

                }

            }

        }

    }


    /* =========================
       REMOVE STAFF
       ========================= */

    elseif ($action == "Remove") {

        $result =
            $database->getUserByEmail(
                $connection,
                $email
            );


        if ($result->num_rows == 0) {

            $message =
                "Account not found.";

        }

        else {

            $user =
                $result->fetch_assoc();


            if ($user["role"] != $staffType) {

                $message =
                    "This account is not a " .
                    $staffType .
                    " account.";

            }

            elseif (
                isset($_SESSION["userEmail"]) &&
                strtolower(
                    $_SESSION["userEmail"]
                ) ==
                strtolower(
                    $user["email"]
                )
            ) {

                $message =
                    "You cannot remove your own account.";

            }

            else {

                $success =
                    $database->deleteUser(
                        $connection,
                        $user["id"]
                    );


                if ($success) {

                    $message =
                        $staffType .
                        " removed successfully.";

                }

                else {

                    $message =
                        "Could not remove " .
                        $staffType .
                        ".";

                }

            }

        }

    }

}



/* =========================
   GET ADMIN LIST
   ========================= */

$adminResult =
    $database->getUsersByRole(
        $connection,
        "Admin"
    );



/* =========================
   GET MODERATOR LIST
   ========================= */

$moderatorResult =
    $database->getUsersByRole(
        $connection,
        "Moderator"
    );


$adminCount =
    $adminResult->num_rows;

$moderatorCount =
    $moderatorResult->num_rows;
