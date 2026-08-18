<html>
<head>

    <title>Create New Account</title>

    <link rel="stylesheet" href="style.css">

</head>
<body>
    <div class="window">
        <div class="header">
            CiviLens - New Account
        </div>
        <div class="content">
            <h1>Create a new account</h1>
            <form>
                <table class="account_table">
                    <tr>
                        <td>Full Name:</td>
                        <td>
                            <input type="text" id="fullname" name="fullname">
                        </td>
                    </tr>
                    <tr>
                        <td>New Password:</td>
                        <td>
                            <input type="password" id="password" name="password">
                        </td>
                    </tr>
                    <tr>
                        <td>Mobile Number:</td>
                        <td>
                            <input type="text" id="mobile" name="mobile">
                        </td>
                    </tr>
                    <tr>
                        <td>Email Address:</td>
                        <td>
                            <input type="email" id="email" name="email">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h2>Address</h2>
                        </td>
                    </tr>
                    <tr>
                        <td>Zila (District):</td>
                        <td>
                            <input type="text" id="district" name="district">
                        </td>
                    </tr>
                    <tr>
                        <td>Upazila:</td>
                        <td>
                            <input type="text" id="upazila" name="upazila">
                        </td>
                    </tr>
                    <tr>
                        <td>Municipality/Union:</td>
                        <td>
                            <input type="text" id="union" name="union">
                        </td>
                    </tr>
                    <tr>
                        <td>Area:</td>
                        <td>
                            <input type="text" id="area" name="area">
                        </td>
                    </tr>
                    <tr>
                        <td>National ID (NID):</td>
                        <td>
                            <input type="text" id="nid" name="nid">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <h2>Security Questions</h2>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            SQ1: What is your favorite movie?
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="text" id="q1" name="q1">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            SQ2: What is your favorite sports team called?
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="text" id="q2" name="q2">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            SQ3: Who was your childhood hero?
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type="text" id="q3" name="q3">
                        </td>
                    </tr>
                </table>
                <div class="buttons">

                    <button  class="back">
                        Back
                    </button>

                    <button  class="next">
                        Next
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
