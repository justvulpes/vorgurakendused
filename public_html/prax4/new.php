<?php
require 'functions.php';
// Start the session
$lifetime = 6000;
setcookie(session_name(), session_id(), time() + $lifetime);
session_start();
?>

    <!doctype html>
    <!--suppress ALL -->
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Developer news</title>
        <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
              integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb"
              crossorigin="anonymous">
        <link href="custom.css" rel="stylesheet">
        <link rel="icon" type="image/png" sizes="32x32" href="../favicon-32x32.png">
    </head>
    <body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
        <a class="navbar-brand" href="../">Practicum IV</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault"
                aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarsExampleDefault">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="index.php">Developer news<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="hot.php">hot<span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
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

    <main role="main">
        <?php if (!array_key_exists("login", $_SESSION)) : ?>
            <!--Main jumbotron for a primary marketing message or call to action-->
            <div class="jumbotron">
                <div class="container">
                    <h1 class="display-3">Welcome to Developer news!</h1>
                    <p>Discover and discuss the latest news!
                        Registered members can submit content to the site such as text posts and images, which are then
                        voted up or down by other members. Submissions with more up-votes appear towards the top.</p>
                    <p><a class="btn btn-primary btn-lg" href="signup.php" role="button">Sign up &raquo;</a></p>
                </div>
            </div>
        <?php else : ?>
            <div class="jumbotron" style="padding: 10px;">
                <div style="padding-left: 15%">
                    <h3 style="padding-right: 50px; padding-top: 10px;">Developer news</h3>
                    <p><a class="btn btn-primary btn-lg" href="submit.php" role="button"
                          style="font-size: 12px; padding-top: 4px; padding-bottom: 4px">Submit
                            a new post &raquo;</a></p>
                </div>
            </div>
        <?php endif; ?>
        <!--newsfeed-->
        <div class="row newsfeed">
            <div class="col-sm-8 blog-main">
                <?php
                $q = "";
                function showNews() {
                    global $q;
                    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
                    if (!$con) {
                        die('Could not connect: ' . mysqli_connect_error());
                    }
                    $q = "SELECT * FROM (SELECT news_id as nid, SUM(score) as scoresum FROM t164036v1_news_score GROUP by news_id ORDER BY scoresum DESC) as gold INNER JOIN t164036v3_news ON t164036v3_news.id=gold.nid GROUP by added DESC;";
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
                                $voteState = 0;
                                $q_score = "SELECT SUM(score) FROM t164036v1_news_score WHERE news_id=" . $row["id"] . ";";
                                $q_comment_count = "SELECT news_id, COUNT(*) as cnt FROM t164036v1_comments WHERE news_id=" . $row["id"] . " GROUP BY news_id;";
                                $comments_count = mysqli_query($con, $q_comment_count)->fetch_assoc();
                                $plural = "s";
                                if ($comments_count['cnt'] === NULL) {
                                    $comments_cnt = "0";
                                } else {
                                    $comments_cnt = $comments_count['cnt'];
                                    $plural = $comments_cnt === "1" ? "" : "s";
                                }
                                $number = mysqli_query($con, $q_score)->fetch_assoc();
                                if (array_key_exists("login", $_SESSION)) {
                                    $q_user_vote = "SELECT score FROM t164036v1_news_score WHERE news_id=" . $row["id"] . " AND username='" . $_SESSION["login"] . "';";
                                    $the_score = mysqli_query($con, $q_user_vote)->fetch_assoc()['score'];
                                    if ($the_score === "1" || $the_score === 1) {
                                        $voteState = 1;
                                    } elseif ($the_score === "-1" || $the_score === -1) {
                                        $voteState = -1;
                                    }
                                }
                                $voteUpClass = 'class="voteup"';
                                $voteDownClass = 'class="votedown"';
                                if ($voteState === 1) {
                                    $voteUpClass = 'class="voteup on"';
                                } elseif ($voteState === -1) {
                                    $voteDownClass = 'class="votedown on"';
                                }
                                echo
                                    '<div class="news-post">
                                    <div class="voting"">
                                        <span ' . $voteUpClass . ' id="voteup' . $row["id"] . '"></span>
                                        <span class="value" id="value' . $row["id"] . '">' . $number['SUM(score)'] . '</span>
                                        <span ' . $voteDownClass . ' id="votedown' . $row["id"] . '"></span>
                                    </div>
                                    <div class="title-meta">
                                        <span class="news-post-title">
                                            <a href="' . 'http://dijkstra.cs.ttu.ee/~rareba/prax4/view.php?id=' . $row["id"] . '">' . $row["title"] . '</a>
                                        </span>
                                        <div class="news-post-meta">
                                        ' . 'Submitted ' . humanTiming(strtotime($row["added"])) . ' ago by ' . '<a href="">' . $row["author"] . '</a>
                                        <br><span style="font-weight: bold">' . $comments_cnt . ' comment' . $plural . '</span>
                                        </div>
                                    </div>
                                </div><!-- /.news-post -->';
                            }
                        } else {
                            echo "No news available :(";
                        }
                        mysqli_close($con);
                    }
                }

                showNews();
                ?>
            </div><!-- /.newsfeed-main -->

            <aside class="col-sm-3 ml-sm-auto blog-sidebar">
                <?php
                $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
                if (!$con) {
                    die('Could not connect: ' . mysqli_connect_error());
                }
                $top_q = "SELECT username, count(username) FROM t164036v1_news_score GROUP by username ORDER by count(username) desc;";
                $res = mysqli_query($con, $top_q);
                echo '
                <div class="sidebar-module">
                    <h4>Most votes</h4>
                    <ol class="list-unstyled">';

                for ($i = 0; $i < 5; $i++) {
                    $row = $res->fetch_assoc();
                    if (isset($row['username']) && $row['username'] !== "") {
                        echo '<li><a href="">' . $row['username'] . '</a> <span style="font-weight: 500;">(' . $row['count(username)'] . '</span> votes)</li>';
                    }
                }
                $most_comments_q = "SELECT author, COUNT(*) AS counts FROM t164036v1_comments GROUP BY author ORDER BY COUNT(*) desc;";
                $comments_result = mysqli_query($con, $most_comments_q);
                echo '
                </ol>
                </div>
                <div class="sidebar-module">
                    <h4>Most comments</h4>
                    <ol class="list-unstyled">';

                for ($i = 0; $i < 5; $i++) {
                    $row = $comments_result->fetch_assoc();
                    $plural = "s";
                    if ($row['counts'] === NULL) {
                        $comments_cnt = "0";
                    } else {
                        $comments_cnt = $row['counts'];
                        $plural = $comments_cnt === "1" ? "" : "s";
                    }
                    if (isset($row['author']) && $row['author'] !== "") {
                        echo '<li><a href="">' . $row['author'] . '</a> <span style="font-weight: 500;">(' . $row['counts'] . '</span> comment' . $plural . ')</li>';
                    }
                }
                echo '
                </ol>
                </div>
                <div class="sidebar-module">
                    <h4>Reference</h4>
                    <ol class="list-unstyled">
                        <li><a href="https://www.reddit.com/">Reddit</a></li>
                        <li><a href="https://news.ycombinator.com/">Hacker news</a></li>
                    </ol>
                </div>
                <script src="features.js"></script>
                <div class="sidebar-module">
                    <h4>Features</h4>
                        <button onclick="party1()" type="button" class="btn btn-light">💙</button>
                        <button onclick="party2()" type="button" class="btn btn-light">💩</button>
                        <button onclick="party3()" type="button" class="btn btn-light">💯</button>
                </div>
                ' ?>
            </aside><!-- /.blog-sidebar -->
        </div><!-- /.row -->
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
            integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
            integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
            crossorigin="anonymous"></script>
    <?php if (array_key_exists("login", $_SESSION)) : ?>
        <script>
            function insertVote(username, news_id, score) {
                $.ajax({
                    data: {"username": username, "news_id": news_id, "score": score},
                    type: "post",
                    url: "http://dijkstra.cs.ttu.ee/~rareba/prax4/insertvote.php",
                    success: function (data) {
//                        console.log(data);
                    }
                });
            }

            $('.voteup').click(function () {
                $(this).toggleClass('on');
                id = $(this).attr('id').replace(/^\D+/g, '');
                voteDownElem = $("#votedown" + id);
                switched = false;
                if (voteDownElem.hasClass('on')) {
                    voteDownElem.removeClass('on');
                    switched = true;
                }
                if ($(this).hasClass('on')) {
                    insertVote(<?php echo '"' . $_SESSION["login"] . '"'?>, parseInt(id), 1)
                } else {
                    insertVote(<?php echo '"' . $_SESSION["login"] . '"'?>, parseInt(id), 0)
                }

                valueElem = $("#value" + id);
                if (switched) {
                    valueElem.text(parseInt(valueElem.text()) + 2)
                    return;
                }
                if ($(this).hasClass('on')) {
                    valueElem.text(parseInt(valueElem.text()) + 1)
                } else {
                    valueElem.text(parseInt(valueElem.text()) - 1)
                }
            });

            $('.votedown').click(function () {
                $(this).toggleClass('on');
                id = $(this).attr('id').replace(/^\D+/g, '');
                voteUpElem = $("#voteup" + id);
                switched = false;
                if (voteUpElem.hasClass('on')) {
                    voteUpElem.removeClass('on');
                    switched = true;
                }
                if ($(this).hasClass('on')) {
                    insertVote(<?php echo '"' . $_SESSION["login"] . '"'?>, parseInt(id), -1)
                } else {
                    insertVote(<?php echo '"' . $_SESSION["login"] . '"'?>, parseInt(id), 0)
                }
                valueElem = $("#value" + id);
                if (switched) {
                    valueElem.text(parseInt(valueElem.text()) - 2)
                    return;
                }
                if ($(this).hasClass('on')) {
                    valueElem.text(parseInt(valueElem.text()) - 1)
                } else {
                    valueElem.text(parseInt(valueElem.text()) + 1)
                }
            });
        </script>
    <?php else : ?>
        <script>
            $('.voteup').click(function () {
                notLoggedInWarning();
            });

            $('.votedown').click(function () {
                notLoggedInWarning();
            });

            function notLoggedInWarning() {
                alert("You must be logged in to vote!");
            }
        </script>
    <?php endif; ?>
    </body>
    </html>
<?php
$q = "";
$c = "";

loginForm();
handleLogout();