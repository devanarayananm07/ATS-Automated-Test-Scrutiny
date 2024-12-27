<?php
include('faculty-session.php');
include('config.php');

// Fetch semester data
$semesterQuery = "SELECT sem_id, semester FROM semester";
$semesterResult = mysqli_query($db, $semesterQuery);

// Fetch subjects data
$subjectsQuery = "SELECT sub_id, subject FROM subjects";
$subjectsResult = mysqli_query($db, $subjectsQuery);
?>

<html>

<head>
    <title>Add Question - Faculty</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
</head>

<body>
    <header class='header'>
        <h2><a class='header-link' href='faculty-home.php'>Faculty</a></h2>
    </header>
    <div class='welcome'>
        <h4 class='align-right'>Welcome, <?php echo $login_session; ?> &emsp; <a href='logout.php'>Log Out</a> &emsp; <a href='faculty-home.php'>Home</a></h4>
    </div>
    <div class='main-content'>
        <div class='question-form'>
            <h2 class='align-center'>Add new question</h2>
            <hr>
            <form action='insert-question.php' method='POST'>
                <label for='prompt'>Question prompt</label>
                <textarea placeholder='Enter question here...' id='prompt' name='prompt' rows='3' cols='100' autocomplete='off' required></textarea>
                <br>
                <label for='exp_ans'>Expected answer</label>
                <textarea placeholder='Enter expected answer here...' id='exp_ans' name='exp_ans' rows='7' cols='100' autocomplete='off' required></textarea>
                <br>
                <label for='marks'>Marks</label>
                <input type='number' id='marks' name='marks' min='1' required>
                <br>

                <!-- New select boxes for semester and subject -->
                <label for='semester'>Select Semester</label>
                <select id='semester' name='sem_id' required>
                    <?php
                    while ($row = mysqli_fetch_assoc($semesterResult)) {
                        echo "<option value='{$row['sem_id']}'>{$row['semester']}</option>";
                    }
                    ?>
                </select>
                <br>
                <label for='subject'>Select Subject</label>
                <select id='subject' name='sub_id' required>
                    <?php
                    while ($row = mysqli_fetch_assoc($subjectsResult)) {
                        echo "<option value='{$row['sub_id']}'>{$row['subject']}</option>";
                    }
                    ?>
                </select>
                <br>

                <p class='align-center'><button type='submit'>Add</button></p>
            </form>
        </div>
    </div>
</body>

</html>