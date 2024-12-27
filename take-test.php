<?php
include('student-session.php');

$test_ID = (int)$_REQUEST['submit'];
$_SESSION['test_ID'] = $test_ID;
$sql = "SELECT * FROM tests WHERE test_ID='$test_ID'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$test_name = $row['test_name'];
$question1_ID = $row['question1_ID'];
$question2_ID = $row['question2_ID'];
$question3_ID = $row['question3_ID'];

$sql = "SELECT prompt, expected_answer FROM questions WHERE question_ID='$question1_ID'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$question1_prompt = $row['prompt'];
$question1_ea = $row['expected_answer'];
$_SESSION['question1_ea'] = $question1_ea;

$sql = "SELECT prompt, expected_answer FROM questions WHERE question_ID='$question2_ID'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$question2_prompt = $row['prompt'];
$question2_ea = $row['expected_answer'];
$_SESSION['question2_ea'] = $question2_ea;

$sql = "SELECT prompt, expected_answer FROM questions WHERE question_ID='$question3_ID'";
$result = mysqli_query($db, $sql);
$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
$question3_prompt = $row['prompt'];
$question3_ea = $row['expected_answer'];
$_SESSION['question3_ea'] = $question3_ea;
?>

<html>

<head>
    <title>Available Tests -Students</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <script>
        // Function to start the timer
        function startTimer(duration, display) {
            var timer = duration,
                minutes, seconds;
            setInterval(function() {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                display.textContent = minutes + ":" + seconds;

                if (--timer < 0) {
                    // Auto-submit the form when the timer reaches 0
                    document.forms["testForm"].submit();
                }
            }, 1000);
        }

        // Execute the timer when the page loads
        document.addEventListener("DOMContentLoaded", function() {
            var twentyMinutes = 60 * 20,
                display = document.querySelector('#timer');
            startTimer(twentyMinutes, display);
        });
    </script>
    <style>
        #timer {
            font-size: 24px;
            font-weight: bold;
            color: #fff;
            background-color: #4CAF50;
            padding: 10px 20px;
            border-radius: 5px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <header class='header'>
        <h2><a class='header-link' href='student-home.php'>Students</a></h2>
        <div id="timer" class="align-right" style="margin-right: 0px;"></div>

    </header>
    <div class='welcome'>
        <h4 class='align-right'>Welcome, <?php echo $login_session; ?> &emsp; <a href='logout.php'>Log Out</a> &emsp; <a href='student-home.php'>Home</a></h4>
    </div>
    <div class='main-content'>
        <div class='take-test-form'>
            <h2 class='align-center'><?php echo $test_name ?></h2>
            <hr>
            <form action='evaluate-test.php' method='POST'>
                <label for='question1_prompt'><b>Question 1: </b><?php echo $question1_prompt ?></label>
                <label for='answer1'><b>Answer:</b></label>
                <textarea placeholder='Please type in your answer here in 500 - 800 words...' id='answer1' name='answer1' rows='7' cols='100' autocomplete='off'></textarea>
                <br>
                <label for='question2_prompt'><b>Question 2: </b><?php echo $question2_prompt ?></label>
                <label for='answer2'><b>Answer:</b></label>
                <textarea placeholder='Please type in your answer here in 500 - 800 words...' id='answer2' name='answer2' rows='7' cols='100' autocomplete='off'></textarea>
                <br>
                <label for='question3_prompt'><b>Question 3: </b><?php echo $question3_prompt ?></label>
                <label for='answer3'><b>Answer:</b></label>
                <textarea placeholder='Please type in your answer here in 500 - 800 words...' id='answer3' name='answer3' rows='7' cols='100' autocomplete='off'></textarea>
                <br>
                <p class='align-center'><input style='width: 10%;' type='submit' name='submit' , value='Submit Test'></p>
            </form>
        </div>
    </div>
</body>

</html>