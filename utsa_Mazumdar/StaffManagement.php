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

$adminResult = $connection->query(
    "SELECT id, fullname, email, role
     FROM users
     WHERE role = 'Admin'
     ORDER BY id ASC"
);



/* =========================
   GET MODERATOR LIST
   ========================= */

$moderatorResult = $connection->query(
    "SELECT id, fullname, email, role
     FROM users
     WHERE role = 'Moderator'
     ORDER BY id ASC"
);


$adminCount =
    $adminResult->num_rows;

$moderatorCount =
    $moderatorResult->num_rows;

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
        href="../Adnan Raad/AdminNewsfeed.php"
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
                href="../Adnan Raad/AdminNewsfeed.php"
            >
                Newsfeed
            </a>

            <a
                href="../S.M. Rahatul Islam/ShowCases.php"
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
                href="../Adnan Raad/UserManagement.php"
            >
                User Management
            </a>

        </div>


        <a
            href="logout.php"
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

$connection->close();

?>