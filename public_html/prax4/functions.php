<?php
function humanTiming($time) {
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
        31536000 => 'year',
        2592000 => 'month',
        604800 => 'week',
        86400 => 'day',
        3600 => 'hour',
        60 => 'minute',
        1 => 'second'
    );

    foreach ($tokens as $unit => $text) {
        if ($time < $unit) continue;
        $numberOfUnits = floor($time / $unit);
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
    return "Error with time!";
}

function loginForm() {
    global $q;
    $username = "unnamed";
    $con = mysqli_connect("localhost", "st2014", "progress", "st2014");
    if (!$con) {
        die('Could not connect: ' . mysqli_connect_error());
    }

    if (isset($_REQUEST["username"]) && $_REQUEST["username"] != ""
        && isset($_REQUEST["password"]) && $_REQUEST["password"] != "") {
//        echo "<br>Login data was valid!<br>";
        $username = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["username"]));
        $password = mysqli_real_escape_string($con, htmlspecialchars($_REQUEST["password"]));
//        echo "Username: $username<br>";
//        echo "Password: $password<br>";
        $q = "SELECT EXISTS (SELECT * FROM t164036v2_users WHERE username='$username' AND password='$password');";
    }
    if ($q === "") {
//        echo "No query";
    } else {
        $result = mysqli_query($con, $q);
        $query_result = $result->fetch_array()[0];
        if ($query_result === "1") {
            print "Successfully logged in!";
            print "<br>";
            $_SESSION["login"] = $username;
            echo
            '<script type="text/javascript">
            window.location = "http://dijkstra.cs.ttu.ee/~rareba/prax4/"
            </script>';
        }
        if (!$result) {
            printf("Error: %s\n", mysqli_error($con));
            exit();
        }
        mysqli_close($con);
    }
}

function handleLogout() {
    if (isset($_REQUEST["logout"])) {
        session_unset();
        session_destroy();
        echo
        '<script type="text/javascript">
        window.location = "http://dijkstra.cs.ttu.ee/~rareba/prax4/"
        </script>';
    }
}
