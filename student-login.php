<?php
error_reporting(E_ERROR | E_PARSE);

include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $myusername = mysqli_real_escape_string($db, $_POST['uname']);
    $mypassword = mysqli_real_escape_string($db, $_POST['pwd']);

    $sql = "SELECT student_ID, password FROM students WHERE student_name = '$myusername'";
    $result = mysqli_query($db, $sql);

    if ($result) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $storedPassword = $row['password'];
        //echo " $storedPassword";
        //echo "$mypassword";

        if (password_verify($mypassword, $storedPassword)) {
            $_SESSION['login_user'] = $myusername;
            header("location: student-home.php");
        } else {
            echo '<script>alert("Your Username or Password is invalid")</script>';
        }
    } else {
        echo '<script>alert("Your Username or Password is invalid")</script>';
    }
}
?>

<html>

<head>
    <title>Students</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
</head>

<body>
    <header class='header'>
        <h2>Student Login</h2>
        <button id="newUserButton">New Here</button>

    </header>
    <div class='main-content'>
        <div class='login-form'>
            <h4>Student Login</h4>
            <hr>
            <form action='' method='POST'>
                <input type='text' placeholder='Username' name='uname' autocomplete='off' required>
                <br>
                <input type='password' placeholder='Password' name='pwd' autocomplete='off' required>
                <p class='align-center'><button type='submit'>Login</button></p>
            </form>
        </div>
    </div>
</body>

</html>
<script>// Select the new user button by its ID or class
const newUserButton = document.getElementById('newUserButton');

// Add a click event listener to the new user button
newUserButton.addEventListener('click', function() {
 // Redirect the user to the signup page
 window.location.href = 'student-signup.php';
});
</script>