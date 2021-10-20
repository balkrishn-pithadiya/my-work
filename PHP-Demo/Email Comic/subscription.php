<?php

    // Imported files 
    /*
    *connection to connect with database
    *send_verification_mail to send mail
    */
    require_once __DIR__.'/connection.php';
    require_once __DIR__.'/send_verification_mail.php';

    // Getting email from index form 
    if(isset($_POST['email']) && !empty($_POST['email'])){

        $email = $_POST['email'];

        // Generated hash_key for verification 
        $hash_key = md5(time().rand());

        // Method filter_var is used to verify the email
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            // Call database and store it in db_conn
            $db_conn = Dbconnection::getInstance()->getConnection();
            if(!$db_conn){
                die('Connection not Established');
            }
                // Check if email already registered
                $query_select = 'SELECT * FROM xkcd_users WHERE email = ?';
                $stmt = $db_conn->prepare($query_select);
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 0) 
                {
                    // If not registered then it will be added in database
                    $query_insert = 'INSERT INTO xkcd_users (email, hash) VALUES (?, ?)';
                    $stmt = $db_conn->prepare($query_insert);
                    $stmt->bind_param('ss', $email, $hash_key);
                    $stmt->execute();
                    if ($stmt->affected_rows > 0) 
                    {
                        // After add mail to database it will automatically send verification mail 
                        $to = $email;
                        $title = 'XKCD Verification';
                        if (isset($_SERVER['HTTP_HOST']))
                        {
                            $link = $_SERVER['HTTP_HOST']."/XKCD-Challenge/verification.php?email=$email&hash=$hash_key";
                        }

                        // Method send_verification_mail is created to send mail to user
                        send_verification_mail($to, $title, $link);
                        echo '
                        <script>alert("Verification Mail Sent To Entered Email Address");
                        window.location.href = "index.php";
                        </script>
                    ';
                    }
                    // Could not get connection with database
                    else 
                    {
                        echo "Error Occured";       
                    }
                }
                // Email is already registered
                else{
                    echo '
                    <script>alert("Email Already Subscribed");
                    window.location.href = "index.php";
                    </script>
                ';
            }
            // Frees the memory associated with result
            $stmt->free_result();

            // Close stmt
            $stmt->close();
        }
        // If valid email is not entered
        else{
            echo '
                <script>alert("Please Enter Valid Email Address");
                window.location.href = "index.php";
                </script>
                ';
            }
            $db_conn->close(); 
    }
?>