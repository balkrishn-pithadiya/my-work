<?php

    //File connection to connect with database
    require_once __DIR__.'/connection.php';

    // Call database and store it in db_conn
    $db_conn = Dbconnection::getInstance()->getConnection();
    if(!$db_conn){
        die('Connection not Established');
    }

    //Get data in variable xkcd_data
    $xkcd_url = 'https://c.xkcd.com/random/comic/';
    $xkcd_data = file_get_contents($xkcd_url);

    $img_header = get_headers($xkcd_url);
    $img = substr($img_header[8], 26);

    // Store image URL
    $image_json_url = "https://xkcd.com/".$img."/info.0.json";

    // Store it in xkcd_data
    $xkcd_data = file_get_contents($image_json_url);

    // Decoding data into JSON
    $json_data = json_decode($xkcd_data, true);

    // Store JSON data of image in variable
    $img_data = $json_data['img'];
    
    // Path where image will be stored
    $path = './Images/sample.png';
    file_put_contents($path, file_get_contents($img_data));

    // Store Comic title in variable
    $title = $json_data['safe_title'];

    if (isset($_SERVER['HTTP_HOST'])){
        $unsubscribe_link = $_SERVER['HTTP_HOST'].'/XKCD-Challenge/unsubscribe.php?email=$to&hash=$hash_key';
    }
    
    // Fetch result from database 
    $result = $db_conn -> query("SELECT * FROM xkcd_users WHERE is_verified = '1'");
    foreach($result as $row){

        // Get mail and hashkey from database
        // to variable will store the email address and we will use it to send mail
        $to = $row['email'];
        $hash_key = $row['hash'];

        // Mail body with image data and title as well as unsubscribe link
        $mail_body = "
                <html>
                    <body>
                        <center>
                            <h1> We Are Glad To Make You Happy </h1>
                            <h1>" . $json_data['title'] . "</h1>
                            <img src='". $img_data ."' alt='". $json_data['alt'] ."'>
                            <a href='<?php echo $unsubscribe_link;?>'><h3>Click Here To Unsubcribe</h3></a>
                        </center><br/>
                    </body>
                </html>
            ";

        // Mail sending syntax
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

        $body = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $mail_body . "\n\n";

        // To attach email attachments in the mail body
        if(!empty($path)){
            if(file_exists($path)) {
                $body .= "--{$mime_boundary}\n";
                $fp = @fopen($path, 'rb');
                $xkcd_data = @fread($fp, filesize($path));

                @fclose($fp);
                $xkcd_data = chunk_split(base64_encode($xkcd_data));
                $body .= "Content-Type: application/octet-stream; name=\"" . basename($path) . "\"\n" .
                    "Content-Description: " . basename($path) . "\n" .
                    "Content-Disposition: attachment;\n" . " filename=\"" . basename($path) . "\"; size=" . filesize($path) . ";\n" .
                    "Content-Transfer-Encoding: base64\n\n" . $xkcd_data . "\n\n";
            } else {
                echo 'File Not Found';
            }
        }else{
            echo 'Image Url Empty';
        }

        $body .= "--{$mime_boundary}--";
        $mail_sent = mail($to, $title, $body, $headers);
        echo $mail_sent?'<h1>Email Sent Successfully!</h1>':'<h1>Email sending failed.</h1>';

    }

?>