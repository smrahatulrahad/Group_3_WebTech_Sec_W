<?php
include "../Controller/CreatePost.php";
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>CivicLens - Create Post</title>

    <!-- <link
        rel="stylesheet"
        href="CSS/CreatePost.css"
    > -->

    <link rel="stylesheet" href="CSS/RahatulStyle.css">

</head>


<body class="createPostPage">


<header class="header">

    <div>

        <h1>CivicLens</h1>

        <p>Create Post</p>

    </div>

    <a
        href="UserNewsfeed.php"
        class="backTop"
    >
        Back to Newsfeed
    </a>

</header>



<div class="pageContainer">


    <aside class="sidebar">


        <div class="userInfo">

            <div class="avatar">

                <?php
                echo strtoupper(
                    substr(
                        $userName,
                        0,
                        1
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

            </div>

        </div>


        <div class="menu">

            <a href="UserNewsfeed.php">
                Newsfeed
            </a>

            <a href="Profile.php">
                Profile
            </a>

            <a href="PendingPosts.php">
                Pending Posts
            </a>

            <a href="ShowCases.php">
                Show Cases
            </a>

            <a href="Donation.php">
                Donation
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

            <h2>Create New Post</h2>

            <p>
                Share a civic issue or important community information.
            </p>


            <?php if ($errorMessage != "") { ?>

                <p>
                    <?php
                    echo htmlspecialchars(
                        $errorMessage
                    );
                    ?>
                </p>

            <?php } ?>

        </div>



        <div class="formCard">

            <form
                action="CreatePost.php"
                method="post"
                enctype="multipart/form-data"
            >


                <div class="formGroup">

                    <label>
                        Post Type
                    </label>

                    <select
                        name="postType"
                        required
                    >

                        <option value="">
                            Select Post Type
                        </option>

                        <option value="Normal Post">
                            Normal Post
                        </option>

                        <option value="Emergency Post">
                            Emergency Post
                        </option>

                    </select>

                    <small>
                        Emergency posts should only be used for urgent situations.
                    </small>

                </div>



                <div class="formGroup">

                    <label>
                        Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter post title"
                        required
                    >

                </div>



                <div class="formGroup">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        placeholder="Describe the issue clearly"
                        required
                    ></textarea>

                </div>



                <div class="formGroup">

                    <label>
                        Contact Info
                    </label>

                    <input
                        type="text"
                        name="contactInfo"
                        placeholder="Phone number or other contact information"
                    >

                </div>



                <div class="locationTitle">

                    <h3>
                        Location Information
                    </h3>

                    <p>
                        Add location details so the issue can be identified easily.
                    </p>

                </div>


                <div class="locationGrid">


                    <div class="formGroup">

                        <label>
                            Division
                        </label>

                        <select name="division">

                            <option value="">
                                Select Division
                            </option>

                            <option>Dhaka</option>

                            <option>
                                Chattogram
                            </option>

                            <option>Khulna</option>

                            <option>
                                Rajshahi
                            </option>

                            <option>
                                Rangpur
                            </option>

                            <option>
                                Mymensingh
                            </option>

                            <option>
                                Sylhet
                            </option>

                            <option>
                                Barishal
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Zila
                        </label>

                        <select name="zila">

                            <option value="">
                                Select Zila
                            </option>

                            <option>
                                Zila 1
                            </option>

                            <option>
                                 Zila 2
                            </option>

                            <option>
                                 Zila 3
                            </option>

                            <option>
                                 Zila 4
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Upazila
                        </label>

                        <select name="upazila">

                            <option value="">
                                Select Upazila
                            </option>

                            <option>
                                Upazila 1
                            </option>

                            <option>
                                Upazila 2
                            </option>

                            <option>
                                Upazila 3
                            </option>

                            <option>
                               Upazila 4
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup">

                        <label>
                            Union / Municipality
                        </label>

                        <select name="union">

                            <option value="">
                                Select Union / Municipality
                            </option>

                            <option>
                                Union-1
                            </option>

                            <option>
                                Union-2
                            </option>

                            <option>
                                Municipality-1
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>



                    <div class="formGroup fullWidth">

                        <label>
                            Area
                        </label>

                        <select name="area">

                            <option value="">
                                Select Area
                            </option>

                            <option>
                                Area-1
                            </option>

                            <option>
                                Area-2
                            </option>

                            <option>
                                Area-3
                            </option>

                            <option>
                                Other
                            </option>

                        </select>

                    </div>


                </div>



                <div class="anonymousBox">

                    <input
                        type="checkbox"
                        name="anonymous"
                        id="anonymous"
                    >

                    <label for="anonymous">
                        Post as Anonymous
                    </label>

                </div>


                <p class="emergencyNote">
                    Anonymous posting is not allowed for Emergency Posts.
                </p>



                <div class="mediaTitle">

                    <h3>
                        Add Media
                    </h3>

                    <p>
                        Photo and video are optional.
                    </p>

                </div>



                <div class="mediaGrid">


                    <div class="formGroup">

                        <label>
                            Upload Photo
                        </label>

                        <input
                            type="file"
                            name="photo"
                            accept=".jpg,.jpeg,.png,.bmp,.gif"
                        >

                    </div>



                    <div class="formGroup">

                        <label>
                            Upload Video
                        </label>

                        <input
                            type="file"
                            name="video"
                            accept=".mp4,.avi,.mov,.wmv,.mkv"
                        >

                    </div>


                </div>



                <div class="formButtons">

                    <button
                        type="submit"
                        class="createButton"
                    >
                        Create Post
                    </button>

                    <a
                        href="UserNewsfeed.php"
                        class="cancelButton"
                    >
                        Cancel
                    </a>

                </div>


            </form>

        </div>


    </main>


</div>


</body>

</html>