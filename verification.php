<?php
    // File connection is used to connect with database 
    require_once __DIR__.'/connection.php';

    // Getting email and hash from URL which is sent with mail
    if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])){

        // Call database and store it in db_conn
        $db_conn = Dbconnection::getInstance()->getConnection();

        // Method filter_var is used to verify the email
        $email = filter_var($_GET['email'], FILTER_SANITIZE_EMAIL);

        // Get hash_key from URL
        $hash_key = $_GET['hash'];

        // Check Database Connection
        if(!$db_conn){
            die('Connection not Established');
        }
        // If connection establish then match email and hash in database
        $query_select = 'SELECT * FROM xkcd_users WHERE email = ? AND hash = ?';
        $stmt = $db_conn->prepare($query_select);
        $stmt->bind_param('ss', $email, $hash_key);
        $stmt->execute();
        $result = $stmt->get_result();

        // If mail found then set verified true
        if($result->num_rows > 0){
            $query_update = 'UPDATE xkcd_users SET is_verified = 1 WHERE email = ? AND hash = ?';
            $stmt = $db_conn->prepare($query_update);
            $stmt->bind_param('ss', $email, $hash_key);
            $stmt->execute();
            if($stmt->affected_rows > 0){
                echo '
                    <script>alert("Your Email Verified Success!");
                    window.location.href = "index.php";
                    </script>
                ';

                // else if email already verified i.e verified is already true
            } else {
                if($stmt->errno == 0){
                    echo '
                    <script>alert("Email Already Verified");
                    window.location.href = "index.php";
                    </script>
                ';

                    // else verification failed problem with connection
                } else {
                    echo '
                    <script>alert("Failed To Verify");
                    window.location.href = "index.php";
                    </script>
                ';
                }
            }

            // Frees the memory associated with result
            $stmt->free_result();

            // Close stmt
            $stmt->close();

            // If email and hash not matches with database 
        } else {
            echo '
                <script>alert("Email Not Found, Please Subscribe To XKCD!");
                window.location.href = "index.php";
                </script>
                ';
        }
        $db_conn->close();
    }
?>