<?php

session_start();


/*
    Registered users are temporarily stored
    in session because database is not used yet.
*/

$registeredUsers = $_SESSION["registeredUsers"] ?? [];
$postStatus = $_SESSION["postStatus"] ?? [];


/* Clear logged-in user information */

session_unset();


/*
    Keep registered accounts available
    after logout.
*/

if (!empty($registeredUsers)) {
    $_SESSION["registeredUsers"] = $registeredUsers;
}


/*
    Keep post approval/rejection status
    after logout.
*/

if (!empty($postStatus)) {
    $_SESSION["postStatus"] = $postStatus;
}


/* Generate a new session ID */

session_regenerate_id(true);


/* Return to login page */

header("Location: ../View/login.php");
exit();

?>