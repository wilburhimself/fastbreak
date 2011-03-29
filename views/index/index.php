<DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8" />
        <title>Fastbreak | Welcome Aboard</title>
        <style type="text/css">
            * {
                margin: 0;
                padding: 0;
            }
            body {
                line-height: 1.3;
                font-size: 62.3%;
                font-family: Tahoma, sans-serif;
                color: #000;
                background: #ddd;
            }
            div#wrap {
                font-size: 1.2em;
                width: 760px;
                margin: 0 auto;
            }
            div#main-content {
                background: #fff;
                border: 3px solid #bbb;
                border-top: none;
                width: 500px;
                float: left;
            }
            div#header {
                padding-left: 30px;
                margin: 20px 20px 30px;
                border-bottom: 1px solid #ddd;
            }
            h1 {
                font-size: 24px;
                padding-bottom: 6px;
            }
            p {
                padding-bottom: 12px;
            }
            div#instructions {
                padding-left: 30px;
                margin: 0 20px 30px;
            }
            ol {
                list-style-type: decimal;
                font-size: 18px;
                margin: 22px 0 0 0;
            }
            ol li {
                color: #bbb;
                padding-bottom: 16px;
            }
            ol li p {
                color: #111;
            }
            div#sidebar {
                float: left;
                padding: 16px;
            }
            #sidebar h3 {
                border-bottom: 1px solid #bbb;
                padding-bottom: 12px;
                margin-bottom: 16px;
            }
            ul {
                list-style: none;
                margin-bottom: 22px;
            }
            ul li {
                padding-bottom: 5px;
            }
        </style>
    </head>
    <body>
        <div id="wrap">

            <div id="main-content">
                <div id="header">
                    <h1>Congratulations!</h1>
                    <p>Your Fastbreak application is running.</p>
                </div>
                <div id="instructions">
                    <h2>Getting started:</h2>
                    <ol>
                        <li><p>Add more actions to this controller in the file <code class="path">controllers/index.php</code>, or create new controllers in the folder <code class="path">controllers</code></p></li>
                        <li><p>Edit this template in the file <code class="path">views/index/index.php</code></p></li>
                        <li><p>Include your database connection information in <code class="path">config/database.php</code></p></li>
                        <li><a href="http://fastbreakphp.com">Read the documentation.</a></li>
                    </ol>
                </div>
            </div>

            <div id="sidebar">
                <h3>Join the community</h3>
                <ul>
                    <li><a href="#">Mailing list</a></li>
                    <li><a href="#">Official Weblog</a></li>
                    <li><a href="#">Wiki</a></li>
                </ul>

                <h3>Browse the documentation</h3>
                <ul>
                    <li><a href="#">Fastbreak Documentation</a></li>
                    <li><a href="#">Fastbreak API</a></li>
                    <li><a href="#">PHP Documentation</a></li>

                </ul>
            </div>

        </div>
    </body>
</html>
