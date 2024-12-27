<?php
include('faculty-session.php');
?>

<html>

<head>
    <title>Available Questions - Faculty</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <style>
        /* Add this style to center the buttons */
        .button-container {
            text-align: center;
            margin-top: 20px;
            /* Adjust the margin as needed */
        }

        /* Style for the buttons */
        .action-button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            /* Green background color */
            color: white;
            /* White text color */
            border: none;
            /* Remove border */
            border-radius: 5px;
            /* Optional: Add rounded corners */
            cursor: pointer;
            /* Add a pointer cursor on hover */
            margin: 5px;
            /* Add margin to separate buttons */
        }

        .action-button.add-subject {
            background-color: #008CBA;
            /* Blue background color for the add subject button */
        }

        .question-table {
            margin-top: 20px;
        }
    </style>
    <script>
        function updateSubjects() {
            var semesterSelect = document.getElementById("semester");
            var subjectSelect = document.getElementById("subject");
            var selectedSemester = semesterSelect.value;

            // Clear existing options
            subjectSelect.innerHTML = "";

            // Fetch subjects based on the selected semester
            <?php
            $subject_query_dynamic = 'SELECT sub_id, subject FROM subjects WHERE sem_id = ?';
            $stmt = mysqli_prepare($db, $subject_query_dynamic);
            mysqli_stmt_bind_param($stmt, "s", $selected_semester);
            mysqli_stmt_execute($stmt);
            $subject_result_dynamic = mysqli_stmt_get_result($stmt);

            while ($subject_row_dynamic = mysqli_fetch_assoc($subject_result_dynamic)) {
                echo 'subjectSelect.options.add(new Option("' . $subject_row_dynamic['subject'] . '", "' . $subject_row_dynamic['sub_id'] . '"));';
            }
            mysqli_stmt_close($stmt);
            ?>

            const btn = document.querySelector("#submitBtn")
            btn.click();
        }
    </script>
</head>

<body>
    <header class='header'>
        <h2><a class='header-link' href='faculty-home.php'>Faculty</a></h2>
    </header>
    <div class='welcome'>
        <h4 class='align-right'>Welcome, <?php echo $login_session; ?>&emsp;<a href='logout.php'>Log Out</a>&emsp;<a href='faculty-home.php'>Home</a></h4>
    </div>
    <div class='main-content'>
        <div style="margin-bottom: 10px;">
            <form method="GET" action="">
                <label for="semester"></label>
                <select id="semester" name="semester" onchange="updateSubjects()">
                    <option value="" disabled selected>Select Semester</option>
                    <?php
                    // Fetch semesters from the database
                    $semester_query = 'SELECT sem_id, semester FROM semester';
                    $semester_result = mysqli_query($db, $semester_query);

                    while ($semester_row = mysqli_fetch_assoc($semester_result)) {
                        $selected = ($_GET['semester'] == $semester_row['sem_id']) ? 'selected' : '';
                        echo '<option value="' . $semester_row['sem_id'] . '" ' . $selected . '>' . $semester_row['semester'] . '</option>';
                    }
                    ?>
                </select>

                <label for="subject"></label>
                <select id="subject" name="subject">
                    <option value="" disabled selected>Select Subject</option>
                    <?php
                    // Fetch subjects based on the selected semester
                    $selected_semester = isset($_GET['semester']) ? $_GET['semester'] : '';
                    $subject_query_dynamic = 'SELECT sub_id, subject FROM subjects WHERE sem_id = ?';
                    $stmt = mysqli_prepare($db, $subject_query_dynamic);
                    mysqli_stmt_bind_param($stmt, "s", $selected_semester);
                    mysqli_stmt_execute($stmt);
                    $subject_result_dynamic = mysqli_stmt_get_result($stmt);

                    while ($subject_row_dynamic = mysqli_fetch_assoc($subject_result_dynamic)) {
                        $selected = ($_GET['subject'] == $subject_row_dynamic['sub_id']) ? 'selected' : '';
                        echo '<option value="' . $subject_row_dynamic['sub_id'] . '" ' . $selected . '>' . $subject_row_dynamic['subject'] . '</option>';
                    }
                    mysqli_stmt_close($stmt);
                    ?>
                </select>

                <div class="button-container">
                    <input type="submit" value="Submit" id="submitBtn" class="action-button">
                    <a href="add-subject.php" class="action-button add-subject">Add Subject</a>
                </div>
            </form>
        </div>

        <?php
        // Retrieve selected semester and subject
        $selected_semester = isset($_GET['semester']) ? $_GET['semester'] : '';
        $selected_subject = isset($_GET['subject']) ? $_GET['subject'] : '';

        $sql = 'SELECT question_ID, prompt, expected_answer FROM questions WHERE sem_id = "' . $selected_semester . '" AND sub_id = "' . $selected_subject . '"';
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) == 0) {
            echo '<h2 class="align-center" style="color: red;">No questions available!</h2>';
        } else {
            echo '<div class="row"><div class="question-table">
                    <form method="POST" action="generate-question-pdf.php">
                    <table>
                    <h2 class="align-center">Available Questions</h2><br>
                        <tr>
                            <th style="width: 120px;">Select</th>
                            <th style="width: 120px;">Question ID</th>
                            <th>Prompt</th>
                            <th>Expected Answer</th>
                        </tr>';

            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                echo '<tr>
                        <td class="align-center">
                            <input type="checkbox" name="selected_questions[]" value="' . $row["question_ID"] . '">
                        </td>
                        <td class="align-center">' . $row["question_ID"] . '</td>
                        <td>' . $row["prompt"] . '</td>
                        <td>' . $row["expected_answer"] . '</td>
                    </tr>';
            }

            echo '</table>';
            echo '<div class="button-container">
                    <input type="submit" name="generate_pdf" value="Generate PDF" class="action-button">
                </div>';
            echo '</form>';
            echo '<p class="align-center"><a href="add-question.php">Add new question</a> &ensp; <a href="remove-question.php">Remove questions</a></p></div></div>';
        }
        ?>
        <div style="text-align: center;">
            <form method="POST" action="generate-question-paper.php">
                <input type="submit" name="generate_papers" value=" Show All Questions">
            </form>
            <form method="POST" action="add-question.php">
                <input type="submit" name="generate_papers" value=" Add New Questions">
            </form>
        </div>
    </div>
</body>

</html>