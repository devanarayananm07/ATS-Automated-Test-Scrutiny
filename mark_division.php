<?php
include('student-session.php');
include('config.php');

// Check if test_id is set in the URL
if (isset($_GET['test_id'])) {
    $test_id = $_GET['test_id'];

    // Fetch scores for the specific test
    $sql = "SELECT tests.test_name, scores.score, scores.q1_score, scores.q2_score, scores.q3_score 
            FROM tests 
            JOIN scores ON tests.test_ID = scores.test_ID 
            WHERE tests.test_ID = ? AND scores.student_ID = ?";

    if ($stmt = $db->prepare($sql)) {
        $stmt->bind_param("ss", $test_id, $login_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            $message = "No scores available for this test.";
        } else {
            $row = $result->fetch_assoc();
            $test_name = $row['test_name'];
            $total_score = $row['score'];
            $q1_score = $row['q1_score'];
            $q2_score = $row['q2_score'];
            $q3_score = $row['q3_score'];
        }

        $stmt->close();
    } else {
        $message = "Error preparing SQL statement.";
    }
} else {
    $message = "Invalid test ID.";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individual Scores - <?php echo isset($test_name) ? htmlspecialchars($test_name) : 'Details'; ?></title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <header class="header">
        <h2><a class="header-link" href="student-home.php">Students</a></h2>
    </header>
    <div class="welcome">
        <h4 class="align-right">Welcome, <?php echo htmlspecialchars($login_session); ?> &emsp; <a href="logout.php">Log Out</a> &emsp; <a href="student-home.php">Home</a></h4>
    </div>
    <div class="main-content">
        <h2 class="align-center">Individual Scores</h2>
        <?php if (isset($message)): ?>
            <h3 class="align-center" style="color: red;"><?php echo htmlspecialchars($message); ?></h3>
        <?php else: ?>
            <h3 class="align-center">Test Name: <?php echo htmlspecialchars($test_name); ?></h3>
            <table class="align-center">
                <tr>
                    <th>Question</th>
                    <th>Score</th>
                </tr>
                <tr>
                    <td>Question 1</td>
                    <td><?php echo htmlspecialchars($q1_score); ?> / 10.00</td>
                </tr>
                <tr>
                    <td>Question 2</td>
                    <td><?php echo htmlspecialchars($q2_score); ?> / 10.00</td>
                </tr>
                <tr>
                    <td>Question 3</td>
                    <td><?php echo htmlspecialchars($q3_score); ?> / 10.00</td>
                </tr>
                <tr>
                    <td><strong>Total Score</strong></td>
                    <td><strong><?php echo htmlspecialchars($total_score); ?> / 30.00</strong></td>
                </tr>
            </table>
        <?php endif; ?>
        <br>
        <div class="align-center">
            <a href="results.php">Back to Results</a>
        </div>
    </div>
</body>

</html>