<?php
session_start();

$userName = $_SESSION["userName"] ?? "Admin User";
$userRole = $_SESSION["userRole"] ?? "Admin";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST["action"] ?? "";
    $staffType = $_POST["staffType"] ?? "";

    if ($action == "Add") {
        $message = $staffType . " will be added after the database is connected.";
    }

    if ($action == "Remove") {
        $message = $staffType . " will be removed after the database is connected.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>CivicLens - Staff Management</title>

    <link rel="stylesheet" href="CSS/StaffManagement.css">

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Staff Management</p>

    </div>


    <a href="../Adnan Raad/AdminNewsfeed.php" class="backTop">
        Back to Admin Newsfeed
    </a>

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

            <a href="../Adnan Raad/AdminNewsfeed.php">
                Newsfeed
            </a>

            <a href="../S.M. Rahatul Islam/ShowCases.php">
                Case Status
            </a>

            <a href="PostApproval.php">
                Post Approval
            </a>

            <a href="StaffManagement.php" class="active">
                Staff Management
            </a>

            <a href="../Adnan Raad/UserManagement.php">
                User Management
            </a>

        </div>


        <a href="login.php" class="logout">
            Logout
        </a>


    </aside>



    <main class="mainContent">


        <div class="pageTitle">

            <div>

                <h2>Staff Management</h2>

                <p>
                    View and manage CivicLens Admin and Moderator accounts.
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



        <?php if ($userRole == "Admin") { ?>


            <section class="staffSection">


                <div class="sectionHeader">

                    <div>

                        <h3>Admin List</h3>

                        <p>
                            Current CivicLens administrator accounts.
                        </p>

                    </div>

                    <span>3 Admins</span>

                </div>



                <div class="tableWrapper">


                    <table>


                        <thead>

                            <tr>

                                <th>Admin ID</th>

                                <th>Email</th>

                                <th>Last Login (UTC)</th>

                            </tr>

                        </thead>



                        <tbody>


                            <tr>

                                <td>1</td>

                                <td>admin1@civiclens.com</td>

                                <td>20 Aug 2026, 03:20 PM</td>

                            </tr>



                            <tr>

                                <td>2</td>

                                <td>admin2@civiclens.com</td>

                                <td>19 Aug 2026, 11:45 AM</td>

                            </tr>



                            <tr>

                                <td>3</td>

                                <td>admin3@civiclens.com</td>

                                <td>18 Aug 2026, 08:10 PM</td>

                            </tr>


                        </tbody>


                    </table>


                </div>



                <form action="StaffManagement.php" method="post" class="manageForm">


                    <input type="hidden" name="staffType" value="Admin">


                    <div class="formGroup">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Enter admin email"
                        >

                    </div>



                    <div class="formGroup">

                        <label>Assigned Password</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Enter password"
                        >

                    </div>



                    <div class="formButtons">

                        <button
                            type="submit"
                            name="action"
                            value="Add"
                            class="addButton"
                        >
                            Add Admin
                        </button>

                        <button
                            type="submit"
                            name="action"
                            value="Remove"
                            class="removeButton"
                        >
                            Remove Admin
                        </button>

                    </div>


                </form>


            </section>


        <?php } ?>



        <section class="staffSection">


            <div class="sectionHeader">

                <div>

                    <h3>Moderator List</h3>

                    <p>
                        Current CivicLens moderator accounts.
                    </p>

                </div>


                <span>3 Moderators</span>

            </div>



            <div class="tableWrapper">


                <table>


                    <thead>

                        <tr>

                            <th>Moderator ID</th>

                            <th>Email</th>

                            <th>Last Login (UTC)</th>

                        </tr>

                    </thead>



                    <tbody>


                        <tr>

                            <td>1</td>

                            <td>moderator1@civiclens.com</td>

                            <td>20 Aug 2026, 05:40 PM</td>

                        </tr>



                        <tr>

                            <td>2</td>

                            <td>moderator2@civiclens.com</td>

                            <td>20 Aug 2026, 10:15 AM</td>

                        </tr>



                        <tr>

                            <td>3</td>

                            <td>moderator3@civiclens.com</td>

                            <td>17 Aug 2026, 07:25 PM</td>

                        </tr>


                    </tbody>


                </table>


            </div>



            <form action="StaffManagement.php" method="post" class="manageForm">


                <input type="hidden" name="staffType" value="Moderator">


                <div class="formGroup">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter moderator email"
                    >

                </div>



                <div class="formGroup">

                    <label>Assigned Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                    >

                </div>



                <div class="formButtons">

                    <button
                        type="submit"
                        name="action"
                        value="Add"
                        class="addButton"
                    >
                        Add Moderator
                    </button>

                    <button
                        type="submit"
                        name="action"
                        value="Remove"
                        class="removeButton"
                    >
                        Remove Moderator
                    </button>

                </div>


            </form>


        </section>


    </main>


</div>


</body>

</html>