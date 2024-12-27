<?php
include('faculty-session.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Extract data from the form
    $subject_id = $_POST['sub_id'];
    $subject_name = $_POST['subject'];
    $semester_id = $_POST['sem_id'];

    // Validate data (add more validation as needed)
    if (empty($subject_id) || empty($subject_name) || empty($semester_id)) {
        echo '<script>alert("All fields are required.");</script>';
    } else {
        // Insert data into the subjects table
        $insert_query = "INSERT INTO subjects (sub_id, subject, sem_id) VALUES ('$subject_id', '$subject_name', '$semester_id')";
        $insert_result = mysqli_query($db, $insert_query);

        if ($insert_result) {
            echo '<script>alert("Subject added successfully.");</script>';
        } else {
            echo '<script>alert("Error adding subject. Please try again.");</script>';
        }
    }
}
?>

<html>
<head>
    <title>Add Subject - Faculty</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
</head>
<body>
    <header class='header'>
        <h2><a class='header-link' href='faculty-home.php'>Faculty</a></h2>
    </header>
    <div class='welcome'>
        <h4 class='align-right'>Welcome, <?php echo $login_session; ?>&emsp;<a href='logout.php'>Log Out</a>&emsp;<a href='faculty-home.php'>Home</a></h4>
    </div>
    <div class='main-content'>
        <div class="add-subject-form">
        <div style="width:100%;display:flex;justify-content:center;align-items:center;margin-top:0.5rem;">
            <h2>Add Subject</h2>
        </div>
            <form method="POST" action="">
                <label for="sub_id">Subject ID:</label>
                <input type="text" id="sub_id" name="sub_id" required>

                <label for="subject">Subject Name:</label>
                <input type="text" id="subject" name="subject" required>

                <label for="sem_id">Semester:</label>
                <select id="sem_id" name="sem_id" required>
                    <?php
                    // Fetch semesters from the database
                    $semester_query = 'SELECT sem_id, semester FROM semester';
                    $semester_result = mysqli_query($db, $semester_query);

                    while ($semester_row = mysqli_fetch_assoc($semester_result)) {
                        echo '<option value="' . $semester_row['sem_id'] . '">' . $semester_row['semester'] . '</option>';
                    }
                    ?>
                </select>

                <div style="display:flex;justify-content:center;align-items:center;margin-top:0.5rem;">
                    <input type="submit" value="Add Subject">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
