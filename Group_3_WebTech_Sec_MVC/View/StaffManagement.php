<?php
include "../Controller/StaffManagement.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CivicLens - Staff Management</title>

    <link
        rel="stylesheet"
        href="CSS/StaffManagement.css"
    >

</head>


<body>


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Staff Management</p>

    </div>


    <a
        href="AdminNewsfeed.php"
        class="backTop"
    >
        Back to Admin Newsfeed
    </a>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">

            <div class="avatar">

                <?php

                echo strtoupper(
                    substr($userName, 0, 1)
                );

                ?>

            </div>


            <div>

                <small>Signed in as</small>

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



        <div class="menu">

            <a
                href="AdminNewsfeed.php"
            >
                Newsfeed
            </a>

            <a
                href="ShowCases.php"
            >
                Case Status
            </a>

            <a href="PostApproval.php">
                Post Approval
            </a>

            <a
                href="StaffManagement.php"
                class="active"
            >
                Staff Management
            </a>

            <a
                href="UserManagement.php"
            >
                User Management
            </a>

        </div>


        <a
            href="../Controller/logout.php"
            class="logout"
        >
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

                <?php

                echo htmlspecialchars(
                    $userRole
                );

                ?>

            </span>

        </div>



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
             ADMIN LIST
             ========================= -->

        <?php if ($userRole == "Admin") { ?>


            <section class="staffSection">


                <div class="sectionHeader">

                    <div>

                        <h3>Admin List</h3>

                        <p>
                            Current CivicLens administrator accounts.
                        </p>

                    </div>


                    <span>

                        <?php echo $adminCount; ?>

                        Admins

                    </span>

                </div>



                <div class="tableWrapper">


                    <table>


                        <thead>

                            <tr>

                                <th>Admin ID</th>

                                <th>Name</th>

                                <th>Email</th>

                            </tr>

                        </thead>



                        <tbody>


                        <?php

                        if ($adminResult->num_rows > 0) {

                            while (
                                $admin =
                                $adminResult->fetch_assoc()
                            ) {

                        ?>

                            <tr>

                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $admin["id"]
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $admin["fullname"]
                                    );

                                    ?>

                                </td>


                                <td>

                                    <?php

                                    echo htmlspecialchars(
                                        $admin["email"]
                                    );

                                    ?>

                                </td>

                            </tr>

                        <?php

                            }

                        }

                        else {

                        ?>

                            <tr>

                                <td colspan="3">

                                    No Admin accounts found.

                                </td>

                            </tr>

                        <?php

                        }

                        ?>


                        </tbody>


                    </table>


                </div>



                <form
                    action="StaffManagement.php"
                    method="post"
                    class="manageForm"
                >


                    <input
                        type="hidden"
                        name="staffType"
                        value="Admin"
                    >


                    <div class="formGroup">

                        <label>Email</label>

                        <input
                            type="email"
                            name="email"
                            placeholder="Enter admin email"
                            required
                        >

                    </div>



                    <div class="formGroup">

                        <label>Password</label>

                        <input
                            type="password"
                            name="password"
                            placeholder="Enter password"
                            required
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



        <!-- =========================
             MODERATOR LIST
             ========================= -->

        <section class="staffSection">


            <div class="sectionHeader">

                <div>

                    <h3>Moderator List</h3>

                    <p>
                        Current CivicLens moderator accounts.
                    </p>

                </div>


                <span>

                    <?php echo $moderatorCount; ?>

                    Moderators

                </span>

            </div>



            <div class="tableWrapper">


                <table>


                    <thead>

                        <tr>

                            <th>Moderator ID</th>

                            <th>Name</th>

                            <th>Email</th>

                        </tr>

                    </thead>



                    <tbody>


                    <?php

                    if (
                        $moderatorResult->num_rows > 0
                    ) {

                        while (
                            $moderator =
                            $moderatorResult->fetch_assoc()
                        ) {

                    ?>

                        <tr>

                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $moderator["id"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $moderator["fullname"]
                                );

                                ?>

                            </td>


                            <td>

                                <?php

                                echo htmlspecialchars(
                                    $moderator["email"]
                                );

                                ?>

                            </td>

                        </tr>

                    <?php

                        }

                    }

                    else {

                    ?>

                        <tr>

                            <td colspan="3">

                                No Moderator accounts found.

                            </td>

                        </tr>

                    <?php

                    }

                    ?>


                    </tbody>


                </table>


            </div>



            <form
                action="StaffManagement.php"
                method="post"
                class="manageForm"
            >


                <input
                    type="hidden"
                    name="staffType"
                    value="Moderator"
                >


                <div class="formGroup">

                    <label>Email</label>

                    <input
                        type="email"
                        name="email"
                        placeholder="Enter moderator email"
                        required
                    >

                </div>



                <div class="formGroup">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        placeholder="Enter password"
                        required
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


<?php


?>