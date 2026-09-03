<?php

class DatabaseConnection
{

    function openConnection()
    {
        $db_host = "localhost";
        $db_user = "root";
        $db_password = "";
        $db_name = "civiclens";


        $connection = new mysqli(
            $db_host,
            $db_user,
            $db_password,
            $db_name
        );


        if ($connection->connect_error) {

            die(
                "Can not connect to the database. " .
                $connection->connect_error
            );

        }


        return $connection;
    }



    /* =========================
       USER FUNCTIONS
       ========================= */


    function registerUser(
        $connection,
        $fullname,
        $email,
        $password,
        $phone,
        $role,
        $district,
        $upazila,
        $union,
        $area,
        $nid,
        $policeRank,
        $station,
        $badge,
        $channel,
        $journalistId,
        $answer1,
        $answer2,
        $answer3
    ) {

        $sql = "INSERT INTO users
        (
            fullname,
            email,
            password,
            phone,
            role,
            district,
            upazila,
            union_name,
            area,
            nid,
            police_rank,
            station_name,
            badge_number,
            channel_name,
            journalist_id,
            security_answer1,
            security_answer2,
            security_answer3
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $statement = $connection->prepare($sql);


        $statement->bind_param(
            "ssssssssssssssssss",
            $fullname,
            $email,
            $password,
            $phone,
            $role,
            $district,
            $upazila,
            $union,
            $area,
            $nid,
            $policeRank,
            $station,
            $badge,
            $channel,
            $journalistId,
            $answer1,
            $answer2,
            $answer3
        );


        return $statement->execute();
    }



    function getUserByEmail(
        $connection,
        $email
    ) {

        $sql =
            "SELECT * FROM users
             WHERE email = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "s",
            $email
        );


        $statement->execute();


        return $statement->get_result();
    }



    function getUserById(
        $connection,
        $id
    ) {

        $sql =
            "SELECT * FROM users
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $id
        );


        $statement->execute();


        return $statement->get_result();
    }



    function getAllUsers(
        $connection
    ) {

        $sql =
            "SELECT * FROM users
             ORDER BY id DESC";


        return $connection->query($sql);
    }



    function updateUserStatus(
        $connection,
        $id,
        $status
    ) {

        $sql =
            "UPDATE users
             SET status = ?
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "si",
            $status,
            $id
        );


        return $statement->execute();
    }



    function deleteUser(
        $connection,
        $id
    ) {

        $sql =
            "DELETE FROM users
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $id
        );


        return $statement->execute();
    }



    function updatePassword(
        $connection,
        $email,
        $password
    ) {

        $sql =
            "UPDATE users
             SET password = ?
             WHERE email = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ss",
            $password,
            $email
        );


        return $statement->execute();
    }



    /* =========================
       POST FUNCTIONS
       ========================= */


    function createPost(
        $connection,
        $userId,
        $postType,
        $title,
        $description,
        $contactInfo,
        $division,
        $zila,
        $upazila,
        $union,
        $area,
        $anonymous,
        $photoPath,
        $videoPath
    ) {

        $sql = "INSERT INTO posts
        (
            user_id,
            post_type,
            title,
            description,
            contact_info,
            division,
            zila,
            upazila,
            union_name,
            area,
            anonymous,
            photo_path,
            video_path
        )
        VALUES
        (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "isssssssssiss",
            $userId,
            $postType,
            $title,
            $description,
            $contactInfo,
            $division,
            $zila,
            $upazila,
            $union,
            $area,
            $anonymous,
            $photoPath,
            $videoPath
        );


        return $statement->execute();
    }



    function getAllPosts(
        $connection
    ) {

        $sql =
            "SELECT posts.*, users.fullname
             FROM posts
             JOIN users
             ON posts.user_id = users.id
             ORDER BY posts.id DESC";


        return $connection->query($sql);
    }



    function getPendingPosts(
        $connection
    ) {

        $sql =
            "SELECT posts.*, users.fullname
             FROM posts
             JOIN users
             ON posts.user_id = users.id
             WHERE posts.status = 'Pending'
             ORDER BY posts.id DESC";


        return $connection->query($sql);
    }



    function getPostsByUser(
        $connection,
        $userId
    ) {

        $sql =
            "SELECT * FROM posts
             WHERE user_id = ?
             ORDER BY id DESC";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $userId
        );


        $statement->execute();


        return $statement->get_result();
    }



    function updatePostStatus(
        $connection,
        $postId,
        $status,
        $reviewedBy
    ) {

        $sql =
            "UPDATE posts
             SET status = ?,
                 reviewed_by = ?,
                 reviewed_at = NOW()
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "sii",
            $status,
            $reviewedBy,
            $postId
        );


        return $statement->execute();
    }



    /* =========================
       POLICE FUNCTIONS
       ========================= */


    function savePoliceCase(
        $connection,
        $postId,
        $policeId,
        $status
    ) {

        $sql = "INSERT INTO police_cases
                (
                    post_id,
                    assigned_police_id,
                    status
                )
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE
                    assigned_police_id = ?,
                    status = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "iisis",
            $postId,
            $policeId,
            $status,
            $policeId,
            $status
        );


        return $statement->execute();
    }



    /* =========================
       JOURNALIST FUNCTIONS
       ========================= */


    function addCoverage(
        $connection,
        $postId,
        $journalistId
    ) {

        $sql =
            "INSERT IGNORE INTO journalist_coverage
             (post_id, journalist_id)
             VALUES (?, ?)";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ii",
            $postId,
            $journalistId
        );


        return $statement->execute();
    }



    function removeCoverage(
        $connection,
        $postId,
        $journalistId
    ) {

        $sql =
            "DELETE FROM journalist_coverage
             WHERE post_id = ?
             AND journalist_id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ii",
            $postId,
            $journalistId
        );


        return $statement->execute();
    }



    /* =========================
       MVC SUPPORT FUNCTIONS
       ========================= */


    function getUsersByRole(
        $connection,
        $role
    ) {

        $sql =
            "SELECT id, fullname, email, role
             FROM users
             WHERE role = ?
             ORDER BY id ASC";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "s",
            $role
        );


        $statement->execute();


        return $statement->get_result();
    }



    function updateCitizenProfile(
        $connection,
        $userId,
        $userName,
        $userPhone,
        $address,
        $district,
        $upazila,
        $nid
    ) {

        $sql =
            "UPDATE users
             SET fullname = ?,
                 phone = ?,
                 address = ?,
                 district = ?,
                 upazila = ?,
                 nid = ?
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ssssssi",
            $userName,
            $userPhone,
            $address,
            $district,
            $upazila,
            $nid,
            $userId
        );


        $success = $statement->execute();
        $statement->close();

        return $success;
    }



    function updateJournalistProfile(
        $connection,
        $userId,
        $userName,
        $userPhone,
        $journalistId,
        $channelName
    ) {

        $sql =
            "UPDATE users
             SET fullname = ?,
                 phone = ?,
                 journalist_id = ?,
                 channel_name = ?
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ssssi",
            $userName,
            $userPhone,
            $journalistId,
            $channelName,
            $userId
        );


        $success = $statement->execute();
        $statement->close();

        return $success;
    }



    function updatePoliceProfile(
        $connection,
        $userId,
        $userName,
        $userPhone,
        $badgeNumber,
        $rank,
        $stationName
    ) {

        $sql =
            "UPDATE users
             SET fullname = ?,
                 phone = ?,
                 badge_number = ?,
                 police_rank = ?,
                 station_name = ?
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "sssssi",
            $userName,
            $userPhone,
            $badgeNumber,
            $rank,
            $stationName,
            $userId
        );


        $success = $statement->execute();
        $statement->close();

        return $success;
    }



    function updatePendingPostByUser(
        $connection,
        $postId,
        $userId,
        $title,
        $postType,
        $description,
        $removeAnonymous
    ) {

        if ($removeAnonymous) {

            $sql =
                "UPDATE posts
                 SET title = ?,
                     post_type = ?,
                     description = ?,
                     anonymous = 0
                 WHERE id = ?
                 AND user_id = ?
                 AND status = 'Pending'";

        }
        else {

            $sql =
                "UPDATE posts
                 SET title = ?,
                     post_type = ?,
                     description = ?
                 WHERE id = ?
                 AND user_id = ?
                 AND status = 'Pending'";

        }


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "sssii",
            $title,
            $postType,
            $description,
            $postId,
            $userId
        );


        if (!$statement->execute()) {
            $statement->close();
            return false;
        }


        $affectedRows = $statement->affected_rows;
        $statement->close();

        return $affectedRows;
    }



    function restorePost(
        $connection,
        $postId
    ) {

        $sql =
            "UPDATE posts
             SET status = 'Pending',
                 reviewed_by = NULL,
                 reviewed_at = NULL
             WHERE id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $postId
        );


        $success = $statement->execute();
        $statement->close();

        return $success;
    }



    function isPostInStatus(
        $connection,
        $postId,
        $status
    ) {

        $sql =
            "SELECT id
             FROM posts
             WHERE id = ?
             AND status = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "is",
            $postId,
            $status
        );


        $statement->execute();
        $result = $statement->get_result();
        $found = $result->num_rows > 0;
        $statement->close();

        return $found;
    }



    function getPostByIdAndStatus(
        $connection,
        $postId,
        $status
    ) {

        $sql =
            "SELECT posts.*, users.fullname
             FROM posts
             JOIN users ON posts.user_id = users.id
             WHERE posts.id = ?
             AND posts.status = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "is",
            $postId,
            $status
        );


        $statement->execute();

        return $statement->get_result();
    }



    function getPoliceCaseByPost(
        $connection,
        $postId
    ) {

        $sql =
            "SELECT assigned_police_id, status
             FROM police_cases
             WHERE post_id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $postId
        );


        $statement->execute();

        return $statement->get_result();
    }



    function getPoliceCaseDetails(
        $connection,
        $postId
    ) {

        $sql =
            "SELECT
                police_cases.status,
                police_cases.assigned_police_id,
                police_cases.updated_at,
                users.fullname AS police_name
             FROM police_cases
             LEFT JOIN users
                ON police_cases.assigned_police_id = users.id
             WHERE police_cases.post_id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $postId
        );


        $statement->execute();

        return $statement->get_result();
    }



    function hasJournalistCoverage(
        $connection,
        $postId,
        $journalistId
    ) {

        $sql =
            "SELECT id
             FROM journalist_coverage
             WHERE post_id = ?
             AND journalist_id = ?";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "ii",
            $postId,
            $journalistId
        );


        $statement->execute();
        $result = $statement->get_result();
        $found = $result->num_rows > 0;
        $statement->close();

        return $found;
    }



    function getJournalistCoverageByPost(
        $connection,
        $postId
    ) {

        $sql =
            "SELECT
                users.fullname,
                journalist_coverage.covered_at
             FROM journalist_coverage
             INNER JOIN users
                ON journalist_coverage.journalist_id = users.id
             WHERE journalist_coverage.post_id = ?
             ORDER BY journalist_coverage.covered_at DESC";


        $statement =
            $connection->prepare($sql);


        $statement->bind_param(
            "i",
            $postId
        );


        $statement->execute();

        return $statement->get_result();
    }



    function getShowCasePosts(
        $connection
    ) {

        $sql =
            "SELECT
                posts.id,
                posts.title,
                police_cases.status AS police_status,
                COUNT(journalist_coverage.id) AS coverage_count
             FROM posts

             LEFT JOIN police_cases
                ON posts.id = police_cases.post_id

             LEFT JOIN journalist_coverage
                ON posts.id = journalist_coverage.post_id

             WHERE posts.status = 'Approved'

             GROUP BY
                posts.id,
                posts.title,
                police_cases.status

             ORDER BY posts.id DESC";


        return $connection->query($sql);
    }

}

?>