<?php

    //File connection to connect with database
    require_once __DIR__.'/config.php';

    // Call database and store it in db_conn
    $db_conn = Dbconnection::getInstance()->getConnection();
    if(!$db_conn){
        die('Connection not Established');
    }

    //Get data in variable url
    $url = 'https://github.com/timeline';
    $data = file_get_contents($url);

    $github_header = get_headers($url);
    $github_api = preg_match('/api.github.com/', $github_header[14], $match);
    $github_api_url = 'https://'.$match[0];

    $opts = [
        'http' => [
                'method' => 'GET',
                'header' => [
                        'User-Agent: PHP'
                    ]
                ]
            ];

    $data = stream_context_create($opts);
    $data = file_get_contents($github_api_url, false, $data);
    $json_data = json_decode($data, true);
    $event_url = $json_data['events_url'];

    $data = stream_context_create($opts);
    $data = file_get_contents($event_url, false, $data);
    $json_data = json_decode($data, true);

    if (isset($_SERVER['HTTP_HOST'])){
        $unsubscribe_link = $_SERVER['HTTP_HOST'].'/github-timeline/unsubscribe.php?email=$to&hash=$hash_key';
    }

    $github_timeline = '';
    foreach($json_data as $item){
        $github_timeline .= "
        <html lang='en'>
            <head>
                <meta charset='utf-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    
                <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css' integrity='sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO' crossorigin='anonymous'>
                <title>Get GitHub Timeline!</title>
                <style>
                    img {
                        width: 50px;
                        height: 50px;
                        margin-right: 10px;
                        border-radius: 5px;
                    }
    
                    .item-row {
                        border-bottom: 1px solid #ccc;
                        padding: 10px 0px;
                        width:100%
                    }
                </style>
            </head>
            <body>
                <div class='container mb-5'>
                <h2>Github Timeline</h2></br></br>
                    <div class='row mt-3 timeline d-flex items'>
                        <div class='row item-row'>
                            <div class='col-md-9 d-flex align-self-center'>
                                <img src='".$item['actor']['avatar_url']."'>
                                <span>
                                    <a href='https://github.com/".$item['actor']['display_login']."' target='_blank'>".$item['actor']['display_login']."</a>
                                    ".getEvent($item['type'], $item['payload'])."
                                    <a href='https://github.com/".$item['repo']['name']."' target='_blank'>".$item['repo']['name']."</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </body>
        </html>
        ";
    }

    function getEvent($type, $payload){
        $event = " ";
        switch($type) {
            case 'WatchEvent':
                $event = 'starred';
                break;
            case 'ForkEvent':
                $event = 'forked <a href="'.$payload['forkee']['html_url'].'">"'.$payload['forkee']['full_name'].'"</a> from';
                break;
            case 'PublicEvent':
                $event = 'made public';
                break;
            case 'CreateEvent':
                $event = 'created a repository';
                break;
            case 'DeleteEvent':
                $event = 'deleted a repository';
                break;
            case 'PullRequestEvent':
                $event = 'opened a pull request in';
                break;
            case 'PushEvent':
                $event = 'pushed a commit';
                break;
            case 'CommitCommentEvent';
                $event = 'commit comment';
                break;
            case 'GollumEvent':
                $event = 'wiki page created';
                break;
            case 'IssueCommentEvent':
                $event = 'issue or pull request comment';
                break;
            case 'IssuesEvent':
                $event = 'opened, closed, reopened, assigned, unassigned, labeled, or unlabeled an issue';
                break;
            case 'MemberEvent':
                $event = 'added or edited member';
                break;
            case 'PullRequestReviewEvent':
                $event = 'created pull request review';
                break;
            case 'PullRequestReviewCommentEvent':
                $event = 'created or edited comment';
                break;
            case 'ReleaseEvent':
                $event = 'published';
                break;
            default:
                $event = '';
        }
        return $event;
    }

    $title = 'Github Timelines!';

    // Fetch result from database 
    $result = $db_conn -> query("SELECT * FROM users_timeline WHERE is_verified = '1'");
    foreach($result as $row){

        // Get mail and hashkey from database
        // to variable will store the email address and we will use it to send mail
        $to = $row['email'];
        $hash_key = $row['hash'];

        $github_timeline .= "<html>
                            <body>
                                <center></br>
                                don't want github updates!
                                <a href='http://localhost/github-timeline/unsubscribe.php?email=$to&hash=$hash_key'> 
                                    <h3>Click Here To Unsubcribe</h3></a>
                                </center></br>
                            </body>
                        </html>";

        // Mail sending syntax
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        $headers .= "\nMIME-Version: 1.0\n" . "Content-Type: multipart/mixed;\n" . " boundary=\"{$mime_boundary}\"";

        $body = "--{$mime_boundary}\n" . "Content-Type: text/html; charset=\"UTF-8\"\n" .
            "Content-Transfer-Encoding: 7bit\n\n" . $github_timeline . "\n\n";

        $body .= "--{$mime_boundary}--";
        $mail_sent = mail($to, $title, $body, $headers);
        echo $mail_sent?'<h1>Email Sent Successfully!</h1>':'<h1>Email sending failed.</h1>';

    }

?>