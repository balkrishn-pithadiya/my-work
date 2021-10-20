<?php

// This function is created to send mail to user
function send_verification_mail($to, $title, $link){

    // body contains the mail body which will be send to user
    $body = "<html>
                <body>
                    Please Use Below Link To Subscribe With XKCD<br />
                    <button><a target='_blank' href=$link>Click Here To Complete Verification</a></button>
                </body>
            </html>";
            
    // headers are required  to send mail
    // it is inbuilt content which id added as parameter with the mail method
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

    // PHP inbuilt method to send mail
    mail($to, $title, $body, $headers);
}
?>