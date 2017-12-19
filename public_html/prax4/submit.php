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
        <title>Submit post</title>
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

    <div class="container" style="padding-top: 10%;">
        <div class="form-submit" style="width: 90%">
            <h2 class="form-signin-heading" style="text-align: center; font-size: 40px">Submit a post</h2>
            <br>
            <div class="btn-group" data-toggle="buttons">
                <label class="btn btn-secondary active">
                    <input type="radio" onfocus="toggleToTextUpload()" name="radioOnText" id="text-option"
                           autocomplete="off"
                           checked>Text post
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" onfocus="toggleToImageUpload()" name="radioOnImage" id="image-option"
                           autocomplete="off">Image post
                </label>
            </div>
            <br>
            <div id="text-or-image">
                <form type="post">
                    <label for="title">Title:</label>
                    <input name="title" type="text" id="inputTitle" class="form-control"
                           autocomplete="off" required
                           autofocus>
                    <br>
                    <label for="content">Text:</label>
                    <textarea name="content" type="text" id="inputText" class="form-control text-justify"
                              autocomplete="off" rows="5"
                              required></textarea>
                    <br>
                    <button class="submit-button btn btn-lg btn-primary btn-block" type="submit">Submit</button>
                </form>
            </div>
        </div>

    </div> <!-- /container -->

    <div style="text-align: center">
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    <script>
        function toggleToImageUpload() {
            $("#text-or-image").replaceWith(`
            <div id="text-or-image">
            <form method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input name="title" type="text" id="inputTitle" class="form-control"
                       autocomplete="off" required
                       autofocus>
                <br>
                <label for="content">Image:</label>
                <label class="custom-file">
                    <input type="file" id="fileToUpload" name="fileToUpload" class="custom-file-input">
                    <span class="custom-file-control"></span>
                </label>
                <!--<button type="button" class="btn btn-info">Upload image</button>-->
                <br>
                <br>
                <button class="submit-button btn btn-lg btn-primary btn-block" value="Upload Image" name="submit" type="submit">Submit</button>
            </form>
            </div>`);
        }

        function toggleToTextUpload() {
            $("#text-or-image").replaceWith(`
            <div id="text-or-image">
                <label for="title">Title:</label>
                <input name="title" type="text" id="inputTitle" class="form-control"
                       autocomplete="off" required
                       autofocus>
                <br>
                <label for="content">Text:</label>
                <textarea name="content" type="text" id="inputText" class="form-control text-justify"
                          autocomplete="off" rows="5"
                          required></textarea>
                <br>
                <button class="submit-button btn btn-lg btn-primary btn-block" type="submit">Submit</button>
            </div>`);
        }
    </script>
    </body>
    </html>
<?php
$q = "";
$c = "";


$q = "";
$q_score_to_news = "";
function submitText() {
    global $q;
    global $q_score_to_news;
    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    if (isset($_REQUEST["title"]) && $_REQUEST["title"] != ""
        && isset($_REQUEST["content"]) && $_REQUEST["content"] != ""
        && array_key_exists("login", $_SESSION)) {
        $title = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["title"]));
        $content = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["content"]));
        $username = $_SESSION['login'];
        $q = "INSERT INTO t164036v3_news (title, content, author) VALUE ('$title', '$content', '$username')";
        mysqli_query($con, $q);
        $q_score_to_news = "INSERT INTO t164036v1_news_score (username, news_id, score) VALUES ('$username', " . mysqli_insert_id($con) . ", 1);";
        mysqli_query($con, $q_score_to_news);
    }
    if ($q === "") {
//        echo "No Squery";
    } else {
//        $result = mysqli_query($con, $q);
//        $result2 = mysqli_query($con, $q_score_to_news);
//        if (!$result || !$result2) {
//            printf("Error: %s\n", mysqli_error($con));
//            exit();
//        }
        echo
        '<script type="text/javascript">
            window.location = "http://dijkstra.cs.ttu.ee/~rareba/prax4/"
        </script>';
    }
    mysqli_close($con);
}

function submitImage() {
    global $q;
    global $q_score_to_news;
    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    if (isset($_REQUEST["title"]) && $_REQUEST["title"] != ""
        && isset($_REQUEST['submit']) && $_REQUEST["submit"] == "Upload Image"
        && array_key_exists("login", $_SESSION)) {
        $title = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["title"]));
        $username = $_SESSION['login'];
        $imageFileType = pathinfo("uploads/" . basename($_FILES["fileToUpload"]["name"]), PATHINFO_EXTENSION);
        $q_for_id = "SELECT MAX(id) as last FROM t164036v3_news;";
        $result_id = mysqli_query($con, $q_for_id);
        $new_id = (int)$result_id->fetch_assoc()['last'] + 1;
        $q = "INSERT INTO t164036v3_news (title, content, author) VALUE ('$title', 'image-$new_id.$imageFileType" . "', '$username')";
        mysqli_query($con, $q);
        $q_score_to_news = "INSERT INTO t164036v1_news_score (username, news_id, score) VALUES ('$username', " . $new_id . ", 1);";
        mysqli_query($con, $q_score_to_news);
        upload_my_file($new_id);
    }
    if ($q === "") {
//        echo "No Squery";
    } else {
//        $result = mysqli_query($con, $q);
//        $result2 = mysqli_query($con, $q_score_to_news);
//        if (!$result || !$result2) {
//            printf("Error: %s\n", mysqli_error($con));
//            exit();
//        }
        echo
        '<script type="text/javascript">
            window.location = "http://dijkstra.cs.ttu.ee/~rareba/prax4/"
        </script>';
    }
    mysqli_close($con);
}

function upload_my_file($fileid) {
    if (!$_FILES) return;

    echo "starting";
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

    echo "<p>$target_file " . $target_file;
    $uploadOk = 1;
    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
    echo "<p>$imageFileType " . $imageFileType;
    $saved_file = $target_dir . $fileid . "." . $imageFileType;
    // Check if image file is a actual image or fake image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    echo "all is fine before checks";
    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file(
            $_FILES["fileToUpload"]["tmp_name"], $saved_file)) {
            echo "<p>The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        } else {
            echo "<p>Sorry, there was an error uploading your file.";
        }
    }
}


loginForm();
handleLogout();
submitText();
submitImage();
