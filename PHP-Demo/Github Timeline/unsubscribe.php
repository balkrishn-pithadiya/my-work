<?php

    //File connection to connect with database
    require_once __DIR__.'/config.php';
    
    // Check if Unsubscribe link is clicked or not
    if(isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['hash']) && !empty($_GET['hash'])) {
        
        // Store email and hash in variables
        $email = $_GET['email'];
        $hash_key = $_GET['hash'];
        
        // Call database and store it in db_conn
        $db_conn = Dbconnection::getInstance()->getConnection();

        // If not connected with database
        if(!$db_conn){
            die('Connection not Established');
        }

        // Query to check if user is subscibed and verified or not
        $query_select = "SELECT * FROM users_timeline WHERE email = ? AND hash = ? AND is_verified = '1'";
        $stmt = $db_conn->prepare($query_select);
        $stmt->bind_param('ss', $email, $hash_key);
        $stmt->execute();
        $result = $stmt->get_result();

        // If user found in database
        if($result->num_rows > 0){

            // Query to delete user from database
            $query_delete = 'DELETE FROM users_timeline WHERE email = ? AND hash = ?';
            $stmt = $db_conn->prepare($query_delete);
            $stmt->bind_param('ss', $email, $hash_key);
            $stmt->execute();

            // If delete query run successfully
            if($stmt->affected_rows > 0){
                echo 'Unsubscribed Successfully';
            } else {
                // else some problem with database operation
                echo 'Unsubscribe Failed';
            }
        } else {
            // else user is not found in database
            echo 'User Not Found.';
        }

        // Frees the memory associated with result
        $stmt->free_result();

        // Close stmt
        $stmt->close();

        // Close database connection
        $db_conn->close();

    }
?>