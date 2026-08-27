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

    header("Location: ../utsa_Mazumdar/login.php");
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

    header("Location: ../utsa_Mazumdar/login.php");
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

    header("Location: ../utsa_Mazumdar/login.php");
    exit();

}


$currentUser = $currentUserResult->fetch_assoc();


/* Disabled account cannot continue */

if ($currentUser["status"] != "Active") {

    $connection->close();

    session_unset();
    session_destroy();

    header("Location: ../utsa_Mazumdar/login.php");
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

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        CivicLens - User Management
    </title>

    <link
        rel="stylesheet"
        href="CSS/UserManagement.css"
    >

</head>


<body>


<!-- =========================
     HEADER
========================= -->

<header class="header">


    <div>

        <h1>
            CivicLens
        </h1>

        <p>
            User Management
        </p>

    </div>


    <a
        href="AdminNewsfeed.php"
        class="backTop"
    >
        Back to Dashboard
    </a>


</header>



<div class="pageContainer">


    <!-- =========================
         SIDEBAR
    ========================= -->

    <aside class="sidebar">


        <div class="userInfo">


            <div class="avatar">

                <?php

                echo htmlspecialchars(
                    strtoupper(
                        substr(
                            $userName,
                            0,
                            1
                        )
                    )
                );

                ?>

            </div>


            <div>


                <small>
                    Signed in as
                </small>


                <strong>

                    <?php

                    echo htmlspecialchars(
                        $userName
                    );

                    ?>

                </strong>


                <span>

                    <?php

                    echo htmlspecialchars(
                        $userRole
                    );

                    ?>

                </span>


            </div>


        </div>



        <!-- =========================
             SAME ADMIN MENU
        ========================= -->

        <div class="menu">


            <a href="AdminNewsfeed.php">
                Newsfeed
            </a>


            <a
                href="../S.M. Rahatul Islam/ShowCases.php"
            >
                Case Status
            </a>


            <a
                href="../utsa_Mazumdar/PostApproval.php"
            >
                Post Approval
            </a>


            <a
                href="../utsa_Mazumdar/StaffManagement.php"
            >
                Staff Management
            </a>


            <a
                href="UserManagement.php"
                class="active"
            >
                User Management
            </a>


        </div>



        <a
            href="../utsa_Mazumdar/logout.php"
            class="logout"
        >
            Logout
        </a>


    </aside>



    <!-- =========================
         MAIN CONTENT
    ========================= -->

    <main class="mainContent">


        <div class="pageTitle">


            <div>

                <h2>
                    User Management
                </h2>

                <p>
                    Manage registered CivicLens user accounts.
                </p>

            </div>


            <span class="roleBadge">

                <?php

                echo htmlspecialchars(
                    $userRole
                );

                ?>

            </span>


        </div>



        <!-- =========================
             MESSAGE
        ========================= -->

        <?php if ($message != "") { ?>


            <div class="message">

                <?php

                echo htmlspecialchars(
                    $message
                );

                ?>

            </div>


        <?php } ?>



        <!-- =========================
             ROLE INFORMATION
        ========================= -->

        <div class="infoBox">


            <?php if ($userRole == "Admin") { ?>

                You are logged in as Admin.
                You can manage all user accounts.

            <?php } ?>


            <?php if ($userRole == "Moderator") { ?>

                You are logged in as Moderator.
                You can manage users, but you cannot
                modify Admin accounts.

            <?php } ?>


        </div>



        <!-- =========================
             USER MANAGEMENT FORM
        ========================= -->

        <form
            action="UserManagement.php"
            method="post"
        >


            <div class="tableCard">


                <div class="tableTitle">


                    <h3>
                        Registered Users
                    </h3>


                    <span>

                        <?php

                        echo $userCount;

                        ?>

                        <?php

                        if ($userCount == 1) {

                            echo " User";

                        }
                        else {

                            echo " Users";

                        }

                        ?>

                    </span>


                </div>



                <div class="tableWrapper">


                    <table>


                        <thead>


                            <tr>

                                <th>
                                    Select
                                </th>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Name
                                </th>

                                <th>
                                    Role
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Action
                                </th>

                            </tr>


                        </thead>



                        <tbody>


                        <?php

                        if (
                            $usersResult &&
                            $usersResult->num_rows > 0
                        ) {


                            while (
                                $user =
                                $usersResult->fetch_assoc()
                            ) {


                                /* Row and status class */

                                if (
                                    $user["status"] == "Active"
                                ) {

                                    $rowClass =
                                        "activeRow";

                                    $statusClass =
                                        "active";

                                }
                                else {

                                    $rowClass =
                                        "disabledRow";

                                    $statusClass =
                                        "disabled";

                                }


                                /* Moderator restriction */

                                $notAllowed = false;


                                if (
                                    $userRole == "Moderator" &&
                                    $user["role"] == "Admin"
                                ) {

                                    $notAllowed = true;

                                }

                        ?>


                            <tr
                                class="<?php
                                    echo htmlspecialchars(
                                        $rowClass
                                    );
                                ?>"
                            >


                                <!-- SELECT -->

                                <td>


                                    <?php if (!$notAllowed) { ?>


                                        <input
                                            type="checkbox"
                                            name="selectedUsers[]"
                                            value="<?php
                                                echo (int)$user["id"];
                                            ?>"
                                        >


                                    <?php } else { ?>


                                        <input
                                            type="checkbox"
                                            disabled
                                        >


                                    <?php } ?>


                                </td>



                                <!-- ID -->

                                <td>

                                    <?php

                                    echo (int)$user["id"];

                                    ?>

                                </td>



                                <!-- NAME -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["fullname"]
                                    );

                                    ?>

                                </td>



                                <!-- ROLE -->

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $user["role"]
                                    );

                                    ?>

                                </td>



                                <!-- STATUS -->

                                <td>


                                    <span
                                        class="status <?php
                                            echo htmlspecialchars(
                                                $statusClass
                                            );
                                        ?>"
                                    >

                                        <?php

                                        echo htmlspecialchars(
                                            $user["status"]
                                        );

                                        ?>

                                    </span>


                                </td>



                                <!-- ACTION -->

                                <td>


                                    <?php if (!$notAllowed) { ?>


                                        <select
                                            name="actions[<?php
                                                echo (int)$user["id"];
                                            ?>]"
                                        >


                                            <option value="None">
                                                None
                                            </option>


                                            <option value="Enable">
                                                Enable
                                            </option>


                                            <option value="Disable">
                                                Disable
                                            </option>


                                            <option value="Remove">
                                                Remove
                                            </option>


                                        </select>


                                    <?php } else { ?>


                                        <select disabled>

                                            <option>
                                                Not Allowed
                                            </option>

                                        </select>


                                    <?php } ?>


                                </td>


                            </tr>


                        <?php

                            }

                        }
                        else {

                        ?>


                            <tr>


                                <td
                                    colspan="6"
                                    style="text-align: center;"
                                >

                                    No registered users found.

                                </td>


                            </tr>


                        <?php

                        }

                        ?>


                        </tbody>


                    </table>


                </div>


            </div>



            <!-- =========================
                 BUTTONS
            ========================= -->

            <div class="bottomButtons">


                <button
                    type="submit"
                    class="applyButton"
                >
                    Apply Changes
                </button>


                <a
                    href="AdminNewsfeed.php"
                    class="backButton"
                >
                    Back
                </a>


            </div>


        </form>


    </main>


</div>


<?php

$connection->close();

?>


</body>

</html>