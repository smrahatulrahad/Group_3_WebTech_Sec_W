<?php
session_start();


if (
    !isset($_SESSION["loggedIn"]) ||
    $_SESSION["loggedIn"] !== true
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


if (
    $_SESSION["userRole"] != "Admin" &&
    $_SESSION["userRole"] != "Moderator"
) {
    header("Location: ../utsa_Mazumdar/login.php");
    exit();
}


$userName = $_SESSION["userName"];
$userRole = $_SESSION["userRole"];

$message = "";

if (!isset($_SESSION["registeredUsers"])) {
    $_SESSION["registeredUsers"] = [];
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $selectedEmail = $_POST["selectedEmail"] ?? "";
    $action = $_POST["action"] ?? "";


    if ($selectedEmail == "") {

        $message = "Please select a user.";

    } elseif (
        !isset($_SESSION["registeredUsers"][$selectedEmail])
    ) {

        $message = "User account not found.";

    } else {

        $selectedRole =
            $_SESSION["registeredUsers"][$selectedEmail]["role"];


        /* Moderator cannot modify Admin accounts */

        if (
            $userRole == "Moderator" &&
            $selectedRole == "Admin"
        ) {

            $message =
                "Moderator cannot modify Admin accounts.";

        } elseif ($action == "Enable") {

            $_SESSION["registeredUsers"][$selectedEmail]["status"]
                = "Active";

            $message = "User enabled successfully.";

        } elseif ($action == "Disable") {

            $_SESSION["registeredUsers"][$selectedEmail]["status"]
                = "Disabled";

            $message = "User disabled successfully.";

        } elseif ($action == "Remove") {

            unset(
                $_SESSION["registeredUsers"][$selectedEmail]
            );

            $message = "User removed successfully.";

        } else {

            $message = "Please select an action.";

        }

    }

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - User Management</title>
    <link rel="stylesheet" href="CSS/UserManagement.css">

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>User Management</p>

    </div>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">

            <div class="avatar">
                <?php echo strtoupper(substr($userName, 0, 1)); ?>
            </div>


            <div>

                <small>Signed in as</small>

                <strong>
                    <?php echo htmlspecialchars($userName); ?>
                </strong>

                <span>
                    <?php echo htmlspecialchars($userRole); ?>
                </span>

            </div>

        </div>


        <div class="menu">

            <a href="AdminNewsfeed.php">
                Newsfeed
            </a>

            <a href="../S.M. Rahatul Islam/ShowCases.php">
                Case Status
            </a>

            <a href="../utsa_Mazumdar/PostApproval.php">
                Post Approval
            </a>

            <a href="../utsa_Mazumdar/StaffManagement.php">
                Staff Management
            </a>

            <a href="UserManagement.php" class="active">
                User Management
            </a>

        </div>


       <a href="../utsa_Mazumdar/logout.php" class="logout-btn">
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <div>

                <h2>User Management</h2>

                <p>
                    Manage registered CivicLens user accounts.
                </p>

            </div>


            <span class="roleBadge">
                <?php echo htmlspecialchars($userRole); ?>
            </span>

        </div>



        <?php if ($message != "") { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>



        <div class="infoBox">

            <?php if ($userRole == "Admin") { ?>

                You are logged in as Admin. You can manage all user accounts.

            <?php } ?>


            <?php if ($userRole == "Moderator") { ?>

                You are logged in as Moderator. You can manage users, but you cannot modify Admin accounts.

            <?php } ?>

        </div>



        <form action="UserManagement.php" method="post">


            <div class="tableCard">


                <div class="tableTitle">

                    <h3>Registered Users</h3>

                    <span>6 Users</span>

                </div>



                <div class="tableWrapper">


                    <table>


                        <thead>

                            <tr>

                                <th>Select</th>

                                <th>ID</th>

                                <th>Name</th>

                                <th>Role</th>

                                <th>Status</th>

                                <th>Action</th>

                            </tr>

                        </thead>



                        <tbody>


                            <tr class="activeRow">

                                <td>
                                    <input type="checkbox" name="user1">
                                </td>

                                <td>1</td>

                                <td>Rahim Ahmed</td>

                                <td>Citizen</td>

                                <td>

                                    <span class="status active">
                                        Active
                                    </span>

                                </td>

                                <td>

                                    <select name="action1">

                                        <option>None</option>

                                        <option>Approve</option>

                                        <option>Enable</option>

                                        <option>Disable</option>

                                        <option>Remove</option>

                                    </select>

                                </td>

                            </tr>



                            <tr class="pendingRow">

                                <td>
                                    <input type="checkbox" name="user2">
                                </td>

                                <td>2</td>

                                <td>Samia Karim</td>

                                <td>Journalist</td>

                                <td>

                                    <span class="status pending">
                                        Pending
                                    </span>

                                </td>

                                <td>

                                    <select name="action2">

                                        <option>None</option>

                                        <option>Approve</option>

                                        <option>Enable</option>

                                        <option>Disable</option>

                                        <option>Remove</option>

                                    </select>

                                </td>

                            </tr>



                            <tr class="activeRow">

                                <td>
                                    <input type="checkbox" name="user3">
                                </td>

                                <td>3</td>

                                <td>Tanvir Hasan</td>

                                <td>Police</td>

                                <td>

                                    <span class="status active">
                                        Active
                                    </span>

                                </td>

                                <td>

                                    <select name="action3">

                                        <option>None</option>

                                        <option>Approve</option>

                                        <option>Enable</option>

                                        <option>Disable</option>

                                        <option>Remove</option>

                                    </select>

                                </td>

                            </tr>



                            <tr class="disabledRow">

                                <td>
                                    <input type="checkbox" name="user4">
                                </td>

                                <td>4</td>

                                <td>Nabila Islam</td>

                                <td>Citizen</td>

                                <td>

                                    <span class="status disabled">
                                        Disabled
                                    </span>

                                </td>

                                <td>

                                    <select name="action4">

                                        <option>None</option>

                                        <option>Approve</option>

                                        <option>Enable</option>

                                        <option>Disable</option>

                                        <option>Remove</option>

                                    </select>

                                </td>

                            </tr>



                            <tr class="activeRow">

                                <td>
                                    <input type="checkbox" name="user5">
                                </td>

                                <td>5</td>

                                <td>Farhan Kabir</td>

                                <td>Moderator</td>

                                <td>

                                    <span class="status active">
                                        Active
                                    </span>

                                </td>

                                <td>

                                    <select name="action5">

                                        <option>None</option>

                                        <option>Approve</option>

                                        <option>Enable</option>

                                        <option>Disable</option>

                                        <option>Remove</option>

                                    </select>

                                </td>

                            </tr>



                            <tr class="activeRow">

                                <td>


                                    <?php if ($userRole == "Admin") { ?>

                                        <input type="checkbox" name="user6">

                                    <?php } ?>


                                    <?php if ($userRole == "Moderator") { ?>

                                        <input type="checkbox" disabled>

                                    <?php } ?>


                                </td>

                                <td>6</td>

                                <td>Mahmud Rahman</td>

                                <td>Admin</td>

                                <td>

                                    <span class="status active">
                                        Active
                                    </span>

                                </td>

                                <td>


                                    <?php if ($userRole == "Admin") { ?>

                                        <select name="action6">

                                            <option>None</option>

                                            <option>Approve</option>

                                            <option>Enable</option>

                                            <option>Disable</option>

                                            <option>Remove</option>

                                        </select>

                                    <?php } ?>


                                    <?php if ($userRole == "Moderator") { ?>

                                        <select disabled>

                                            <option>Not Allowed</option>

                                        </select>

                                    <?php } ?>


                                </td>

                            </tr>


                        </tbody>


                    </table>


                </div>


            </div>



            <div class="bottomButtons">

                <button type="submit" class="applyButton">
                    Apply Changes
                </button>

                <a href="AdminNewsfeed.php" class="backButton">
                    Back
                </a>

            </div>


        </form>


    </main>


</div>


</body>

</html>