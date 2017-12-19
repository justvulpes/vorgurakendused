<?php

if (isset($_POST["username"]) && isset($_POST["news_id"]) && isset($_POST["score"])) {
    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }
    $q = "INSERT INTO t164036v1_news_score (username, news_id, score) 
              VALUES ('" . $_POST["username"] . "', " . $_POST["news_id"] . ", " . $_POST["score"] . ") 
              ON DUPLICATE KEY UPDATE username='" . $_POST["username"] . "'," . "score=" . $_POST["score"] . ";";
//    echo $q;
    mysqli_query($con, $q);
}
