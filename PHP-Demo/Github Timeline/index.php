
<!DOCTYPE html>
 
 <html>
    <head>
        <meta charset="ISO-8859-1">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Github Timeline</title>
        <style>
            
            body{
                margin: 0px;
                padding: 0px;
                text-align:center;
                width: 100%;
                background-color: #151E3D;
                }
                
            input[type=text]{
                width:20%;
                padding:7px 10px;
                margin: 10px 0;
                display:inline-block;
                border: 1px solid #ccc;
                box-sizing: border-box;
                }
                
            button{
                background-color:#4CAF50;
                width: 10%;
                padding: 9px 5px;
                margin:10px 0px 0px 35px;
                cursor:pointer;
                border:none;
                color:#ffffff;
                font-size: 15px;
                font-weight: bold;
                }
                
            button:hover{
                opacity:0.8;
                }
                
            #un,#ps{
                font-family:'Lato', sans-serif;
                color: white;
                }
            
            
            #container{
                position: absolute;
                top:0;
                bottom: 0;
                left: 0;
                right: 0;
                margin: auto;
                width:600;
                height: 300px;
                text-align: center;
                }

        </style>
    </head>
    <body> 
        <div id="container">
            <form action="subscribe.php" method="post">

                <div class="border-box">
                    <h2 id="un">Github Timeline Subscription</h2>
                    <label id="un">Email:</label>
                    <input type="email" name="email" placeholder="Enter Email Address" id="uname"><br/>
                    <button type="submit" value="submit" id="submit">Submit</button>
                </div>
            </form>
        </div>
    </body>
</html>