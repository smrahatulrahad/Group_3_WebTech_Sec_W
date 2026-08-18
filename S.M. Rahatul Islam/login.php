<html>
<head>
    <title>Sign In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="login_window">
 
        <div class="login_header">
            CiviLens - Sign In
        </div>
 
        <div class="login_content">
            <h1 id="login_title">
                WELCOME TO CIVICLENS
            </h1>
            <p id="login_subtitle">
                Multimedia Complaint Management Tracking System
            </p>
            <div class="login_form_box">
                <form method="post" action="">
                    <table class="login_table">
                        <tr>
                            <td>
                                Email Address :
                            </td>
                            <td>
                                <input
                                    type="email"
                                    id="login_email"
                                    name="email"
                                >
                                <p id="email_error" class="login_error">
                                    <?php echo $emailError; ?>
                                </p>
                            </td>
                        </tr>
 
                        <tr>
                            <td>
                                Password :
                            </td>
 
                            <td>
                                <input type ="password" id="login_password"  name="password">
       
 
                                <p id="password_error" class="login_error">
                                    <?php echo $passwordError; ?>
                                </p>
                            </td>
                        </tr>
 
                    </table>
 
                    <div class="login_buttons">
 
                        <button type="submit"  id="login_button" class="login_button">
                             Login
                        </button>
 
                        <button type="button" id="signup_button"  class="signup_button">
                            Sign Up
                        </button>
                    </div>
                </form>
                <div class="user_buttons">
                    <button type="button" id="citizen_button" class="citizen_button">    
                        Citizen
                    </button>
                    <button type="button" id="journalist_button" class="journalist_button">
                        Journalist
                    </button>
 
                    <button type="button" id="police_button"  class="police_button">
                        Police
                    </button>
                </div>
                <a href="#forgot"id="forgot_link" class="forgot_link">
                    Forgot password?
                </a>
            </div>
        </div>
    </div>
</body>
</html>