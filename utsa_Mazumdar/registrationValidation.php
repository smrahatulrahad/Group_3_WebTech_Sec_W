<?php

session_start();




if ($_SERVER["REQUEST_METHOD"] == "POST") {


    $role = $_POST["role"];

    $fullname = $_POST["fullname"];

    $email = $_POST["email"];

    $password = $_POST["password"];

    $phone = $_POST["phone"];


    $q1 = $_POST["q1"];

    $q2 = $_POST["q2"];

    $q3 = $_POST["q3"];



    /* Save common information */

    $_SESSION["fullname"] = $fullname;

    $_SESSION["email"] = $email;

    $_SESSION["password"] = $password;

    $_SESSION["phone"] = $phone;

    $_SESSION["role"] = $role;



    /* Save security answers */

    $_SESSION["q1"] = $q1;

    $_SESSION["q2"] = $q2;

    $_SESSION["q3"] = $q3;



    /* Citizen information */

    if ($role == "citizen") {

        $_SESSION["district"] = $_POST["district"];

        $_SESSION["upazila"] = $_POST["upazila"];

        $_SESSION["union"] = $_POST["union"];

        $_SESSION["area"] = $_POST["area"];

        $_SESSION["nid"] = $_POST["nid"];

    }



    /* Police information */

    if ($role == "police") {

        $_SESSION["rank"] = $_POST["rank"];

        $_SESSION["station"] = $_POST["station"];

        $_SESSION["badge"] = $_POST["badge"];

    }


    

     
    

    /* Journalist information */

    if ($role == "journalist") {

        $_SESSION["channel"] = $_POST["channel"];

        $_SESSION["journalist_id"] =
            $_POST["journalist_id"];

    }



    /* Go to login page */

    header("Location: ../View/login.php");

    exit();

}

?>