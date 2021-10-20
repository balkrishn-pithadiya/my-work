<?php

    // Import files 
    /*
    *connection to connect with database
    */
    require_once __DIR__.'/config.php';

    // Check whether user has entered email or not 
    if(isset($_POST['email']) && !empty($_POST['email'])){

        $email = $_POST['email'];

        // Generate hash_key for verification 
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
                $query_select = 'SELECT * FROM users_timeline WHERE email = ?';
                $stmt = $db_conn->prepare($query_select);
                $stmt->bind_param('s', $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if($result->num_rows == 0) 
                {
                    // If not registered then it will be added in database
                    $query_insert = 'INSERT INTO users_timeline (email, hash) VALUES (?, ?)';
                    $stmt = $db_conn->prepare($query_insert);
                    $stmt->bind_param('ss', $email, $hash_key);
                    $stmt->execute();
                    if ($stmt->affected_rows > 0) 
                    {
                        // After add mail to database it sends verification mail 
                        $to = $email;
                        $title = 'Verification';
                        if (isset($_SERVER['HTTP_HOST']))
                        {
                            $link = $_SERVER['HTTP_HOST']."/github-timeline/verify.php?email=$email&hash=$hash_key";
                        }

                        // body contains the mail body which will be send to user
                        $body = "<html>
                            <body>
                                Please Use Below Link To Subscribe<br />
                                <button><a target='_blank' href=$link>Click Here To Complete Verification</a></button>
                            </body>
                        </html>";

                        // headers are required  to send mail
                        // it is inbuilt content which id added as parameter with the mail method
                        $headers = "MIME-Version: 1.0" . "\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                        // PHP inbuilt method to send mail
                        mail($to, $title, $body, $headers);
                        echo '
                        <script>alert("Verification Mail Sent Successfully!");
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
        // Close database connection
        $db_conn->close(); 
    }
?>