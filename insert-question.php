<?php
include('faculty-session.php');
include('config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize your input data
    $prompt = mysqli_real_escape_string($db, $_POST['prompt']);
    $exp_ans = mysqli_real_escape_string($db, $_POST['exp_ans']);
    $marks = intval($_POST['marks']);
    $semester = mysqli_real_escape_string($db, $_POST['sem_id']);
    $subject = mysqli_real_escape_string($db, $_POST['sub_id']);

    // Check if the semester exists
    $checkSemesterQuery = "SELECT COUNT(*) as count FROM semester WHERE sem_id = ?";
    if ($stmt = mysqli_prepare($db, $checkSemesterQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $semester);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $semesterExists = mysqli_fetch_assoc($result)['count'];

        if ($semesterExists) {
            // Check if the subject exists
            $checkSubjectQuery = "SELECT COUNT(*) as count FROM subjects WHERE sub_id = ?";
            if ($stmt = mysqli_prepare($db, $checkSubjectQuery)) {
                mysqli_stmt_bind_param($stmt, "s", $subject);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $subjectExists = mysqli_fetch_assoc($result)['count'];

                if ($subjectExists) {
                    // Insert the question into the questions table
                    $insertQuery = "INSERT INTO questions (prompt, expected_answer, marks, faculty_ID, sem_id, sub_id)
                                    VALUES (?, ?, ?, ?, ?, ?)";
                    if ($stmt = mysqli_prepare($db, $insertQuery)) {
                        $facultyID = $login_id;
                        mysqli_stmt_bind_param($stmt, "ssiiis", $prompt, $exp_ans, $marks, $facultyID, $semester, $subject);
                        $success = mysqli_stmt_execute($stmt);

                        if ($success) {
                            echo '<script>alert("Question added successfully.");</script>';
                        } else {
                            echo '<script>alert("Error adding question: ' . mysqli_error($db) . '");</script>';
                        }
                    } else {
                        echo '<script>alert("Error preparing insert statement: ' . mysqli_error($db) . '");</script>';
                    }
                } else {
                    echo '<script>alert("Subject does not exist.");</script>';
                }
            } else {
                echo '<script>alert("Error preparing subject check statement: ' . mysqli_error($db) . '");</script>';
            }
        } else {
            echo '<script>alert("Semester does not exist.");</script>';
        }
    } else {
        echo '<script>alert("Error preparing semester check statement: ' . mysqli_error($db) . '");</script>';
    }
} else {
    echo '<script>alert("Invalid request method.");</script>';
}

// Redirect to the questions page after handling
header("Location: questions.php");
exit();
