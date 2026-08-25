<?php

session_start();


$resetError = "";



if (isset($_SESSION["resetError"])) {

    $resetError = $_SESSION["resetError"];

    unset($_SESSION["resetError"]);

}



?>

<!DOCTYPE html>
<html>

<head>

    <title>CiviLens - Reset Password</title>

    <link rel="stylesheet"
          href="style.css">

</head>


<body>


<div class="window_reset">


    <div class="header_reset">

        CiviLens - Reset Password

    </div>



    <div class="reset_content">


        <div class="reset_left">


            <h1>

                RESET<br>
                PASSWORD

            </h1>


            <p>

                Recover your CiviLens account
                using your security questions.

            </p>


            <p>

                Enter the correct answers and
                create a new password.

            </p>


        </div>



        <div class="reset_right">


            <h1>

                Reset your password

            </h1>



            <?php

            if ($resetError != "") {

                echo "<p class='reset_error'>";
                echo $resetError;
                echo "</p>";

            }

            ?>



            <form method="post"
                  action="../Controller/resetPasswordController.php">


                <table class="reset_table">


                    <tr>

                        <td>
                            Email Address:
                        </td>

                        <td>

                            <input type="email"
                                   name="email"
                                   required>

                        </td>

                    </tr>



                    <tr>

                        <td colspan="2">

                            <h2>
                                Security Questions
                            </h2>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            Favorite movie?
                        </td>

                        <td>

                            <input type="text"
                                   name="q1"
                                   required>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            Favorite sports team?
                        </td>

                        <td>

                            <input type="text"
                                   name="q2"
                                   required>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            Childhood hero?
                        </td>

                        <td>

                            <input type="text"
                                   name="q3"
                                   required>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            New Password:
                        </td>

                        <td>

                            <input type="password"
                                   name="new_password"
                                   required>

                        </td>

                    </tr>



                    <tr>

                        <td>
                            Confirm Password:
                        </td>

                        <td>

                            <input type="password"
                                   name="confirm_password"
                                   required>

                        </td>

                    </tr>


                </table>



                <div class="buttons">


                    <a href="login.php"
                       class="back">

                        Back

                    </a>



                    <button type="submit"
                            class="reset_btn">

                        Reset Password

                    </button>


                </div>


            </form>


        </div>

    </div>

</div>


</body>

</html>