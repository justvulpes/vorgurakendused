<?php
require 'functions.php';
// Start the session
$lifetime = 6000;
session_set_cookie_params($lifetime);
session_start();
?>

    <!doctype html>
    <!--suppress ALL -->
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Signup</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
              integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
              crossorigin="anonymous">
        <link href="custom.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    </head>
    <body class="centered-body">

    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="../">Practicum IV</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Developer news<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">hot<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="new.php">new<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="top.php">top<span class="sr-only">(current)</span></a>
                </li>
            </ul>
            <?php if (array_key_exists("login", $_SESSION)) : ?>
                <?php
                echo '<span class="nav-item" style="color:white;margin-right: 1%">Logged in as <span style="color: lightgreen">' . $_SESSION["login"] . '</span></span>';
                ?>
                <form autocomplete="off" method="post">
                    <input type="hidden" name="logout">
                    <button class="btn btn-outline-success mr-sm-2 my-sm-0" type="submit">Log out</button>
                </form>
            <?php else : ?>
                <form class="form-inline my-2 my-lg-0" method="post">
                    <input name="username" class="form-control mr-sm-2" type="text" placeholder="Username"
                           autocomplete="off">
                    <input name="password" class="form-control mr-sm-2" type="password" placeholder="Password"
                           autocomplete="off">
                    <button class="btn btn-outline-success mr-sm-2 my-sm-0" type="submit">Login</button>
                </form>
                <form action="signup.php">
                    <button class="btn btn-outline-success mr-sm-2 my-sm-0" type="submit">Sign up</button>
                </form>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container" style="padding-top: 10%">

        <form class="form-signin" method="post">
            <h2 class="form-signin-heading" style="text-align: center; font-size: 40px">Sign up</h2>
            <br>
            <input name="fullname" type="text" id="inputFullname" class="form-control" placeholder="Full name"
                   autocomplete="off" required
                   autofocus>
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email address"
                   autocomplete="off"
                   required>
            <input name="username" type="text" id="inputUsername" class="form-control" placeholder="Username"
                   autocomplete="off" required>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Password"
                   autocomplete="off"
                   required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
        </form>

    </div> <!-- /container -->

    <div style="text-align: center">
        <?php


        $q = "";
        $c = "";

        //    main();

        function insertForm() {
            global $q;
            $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
            if (!$con) {
                die('Could not connect: ' . mysqli_connect_error());
            }
            foreach ($_POST as $key => $value)
//                echo "Field " . htmlspecialchars($key) . " is " . htmlspecialchars($value) . "<br>";
            if (isset($_REQUEST["fullname"]) && $_REQUEST["fullname"] != ""
                && isset($_REQUEST["email"]) && $_REQUEST["email"] != ""
                && isset($_REQUEST["username"]) && $_REQUEST["username"] != ""
                && isset($_REQUEST["password"]) && $_REQUEST["password"] != "") {
//                echo "<br>Data was valid!<br>";
                $fullname = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["fullname"]));
                $email = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["email"]));
                $username = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["username"]));
                $password = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["password"]));
                $q = "INSERT INTO t164036v2_users (fullname,email,username,password) VALUES('$fullname', '$email', '$username', '$password');";
            }
            if ($q == "") {
//                echo "No query";
            } else {
//                echo "query: " . $q . "<br>";
                $result = mysqli_query($con, $q);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($con));
                    exit();
                }
                mysqli_close($con);
            }
        }

        insertForm();

        ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    </body>
    </html>
<?php
$q = "";
$c = "";


loginForm();
handleLogout();