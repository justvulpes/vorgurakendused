<?php
require 'functions.php';
// Start the session
$lifetime = 6000;
session_set_cookie_params($lifetime);
session_start();


function getNewsData($column) {
    $q = "";
    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    if (isset($_REQUEST["id"]) && is_numeric($_REQUEST["id"])) {
        $id = $_REQUEST["id"];
        $q = "SELECT * from t164036v3_news WHERE id=$id;";
    }
    if ($q === "") {
        return "Invalid id!";
    } else {
        $result = mysqli_query($con, $q);
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        $content = mysqli_fetch_object($result);
        if (!$content) return "Invalid id!";
        mysqli_close($con);
        return $content->$column;
    }
}

function displayContent() {
    $content = getNewsData("content");
    if (substr($content, 0, 5) === "image") {
        $fileName = substr($content, 6);
        $result = '<br><img width=300 height=300 src="uploads/' . $fileName . '"><br><br><br>';
    } else {
        $result = '<textarea readonly name="content" id="inputText" class="news-box text-justify" 
               required>' . getNewsData("content") . '</textarea>';
    }
    return $result;
}

?>
    <!doctype html>
    <!--suppress ALL -->
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>News</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
              integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
              crossorigin="anonymous">
        <link href="custom.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    </head>
    <body class="centered-body-view">

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
                echo '<span class="nav-item" style="color:white;margin-right: 1%">Logged in as <span
                      style="color: lightgreen">' . $_SESSION["login"] . '</span></span>';
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
        <h2 class="form-signin-heading" style="text-align: left; font-size: 30px">
            <?php echo getNewsData("title"); ?>
        </h2>
        <span class="form-signin-heading small-text">
            submitted <?php echo humanTiming(strtotime(getNewsData("added"))) ?> ago by <a
                    href=""><?php echo getNewsData("author"); ?></a>
        </span>
        <br>
        <div>
            <?php
            echo displayContent();
            ?>
        </div>
        <?php
        $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
        if (!$con) {
            die('Could not connect: ' . mysqli_connect_error());
        }
        $q_comment_count = "SELECT news_id, COUNT(*) as cnt FROM t164036v1_comments WHERE news_id=" . $_REQUEST["id"] . " GROUP BY news_id;";
        $comments_count = mysqli_query($con, $q_comment_count)->fetch_assoc();
        $plural = "s";
        if ($comments_count['cnt'] === NULL) {
            $comments_cnt = "0";
        } else {
            $comments_cnt = $comments_count['cnt'];
            $plural = $comments_cnt === "1" ? "" : "s";
        }
        echo '<span class="small-text">' . $comments_cnt . ' comment' . $plural . ' </span>'
        ?>
        <br>
        <br>
        <?php if (array_key_exists("login", $_SESSION)) : ?>
            <?php
            echo '
            <form method="get">
                <input type="hidden" name="id" value="' . $_REQUEST["id"] . '">
                <textarea name="content" id="inputText" class="comment-box form-control text-justify"
                          autocomplete="off" rows="5"
                          required></textarea>
                <br>
                <button class="comment-button btn btn-lg btn-primary btn-block" type="submit">Add comment</button>
            </form>'
            ?>
        <?php else : ?>
            <p style="color: red;">
                Login to comment!
            </p>
        <?php endif; ?>

        <?php
        $q = "";
        $c = "";

        function insertComment() {
            global $q;
            $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
            if (!$con) {
                die('Could not connect: ' . mysqli_connect_error());
            }
            if (isset($_REQUEST["content"]) && $_REQUEST["content"] != ""
                && (array_key_exists("login", $_SESSION))) {
                $content = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["content"]));
                $author = $_SESSION["login"];
                $news_id = getNewsData('id');
                echo '<br>';
                echo '<br>';
                echo 'Debug:';
                echo '<br>';
                echo $content;
                echo '<br>';
                echo $author;
                echo '<br>';
                echo $news_id;
                echo '<br>';
                $q = "INSERT INTO t164036v1_comments (news_id, content, author) VALUES ('$news_id', '$content', '$author');";
            }
            if ($q == "") {
                //        echo "No query";
//                echo
//                    '<script type="text/javascript">
//                    window.location = ' . '"http://dijkstra.cs.ttu.ee/~rareba/prax4/view.php?id=' . getNewsData('id') . '";
//                </script>';
            } else {
                $result = mysqli_query($con, $q);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($con));
                    exit();
                }
                mysqli_close($con);
                echo
                    '<script type="text/javascript">
                    window.location = ' . '"http://dijkstra.cs.ttu.ee/~rareba/prax4/view.php?id=' . getNewsData('id') . '";
                </script>';
            }
        }

        insertComment();
        ?>
        <?php
        $q = "";
        function showComments() {
            global $q;
            $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
            if (!$con) {
                die('Could not connect: ' . mysqli_connect_error());
            }
            $q = "SELECT * FROM t164036v1_comments WHERE news_id=" . $_REQUEST["id"] . " ORDER BY added DESC;";
            if ($q === "") {
//                        echo "No query";
            } else {
                $result = mysqli_query($con, $q);
                if (!$result) {
                    printf("Error: %s\n", mysqli_error($con));
                    exit();
                }
                if ($result->num_rows > 0) {
                    // output data of each row
                    while ($row = $result->fetch_assoc()) {
                        echo '
                        <div class="comment">
                        <div class="comment-meta"><a href="">' . $row["author"] . '</a> ' . humanTiming(strtotime($row["added"])) . ' ago' . '</div>
                        <div class="comment-content">' . $row['content'] . '</div></div>
                        ';
                    }
                } else {
                    echo "<br>No comments.";
                }
                mysqli_close($con);
            }
        }

        showComments();
        ?>
    </div> <!-- /container -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    <script>
        function setHeight(jq_in) {
            jq_in.each(function (index, elem) {
                // This line will work with pure Javascript (taken from NicB's answer):
                elem.style.height = (parseInt(elem.scrollHeight) + 10) + 'px';
            });
        }

        setHeight($('#inputText'));
    </script>
    </body>
    </html>
<?php
$q = "";
$c = "";


loginForm();
handleLogout();