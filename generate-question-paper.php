<?php
error_reporting(E_ERROR | E_PARSE);

include('faculty-session.php');
include('config.php');

// Define the number of questions to display per page
$questionsPerPage = 15;

// Get the current page number
$current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;

// Calculate the offset for the SQL query
$offset = ($current_page - 1) * $questionsPerPage;

// Fetch questions for the current page
$sql = "SELECT question_ID, prompt, marks FROM questions LIMIT $offset, $questionsPerPage";
$result = mysqli_query($db, $sql);

?>

<html>
<head>
    <title>Generate Question Paper - Faculty</title>
    <meta charset='UTF-8'>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <header class='header'>
        <h2><a class='header-link' href='faculty-home.php'>Faculty</a></h2>
    </header>
    <div class='welcome'>
        <h4 class='align-right'>Welcome, <?php echo $login_session; ?> &emsp; <a href='logout.php'>Log Out</a> &emsp; <a href='faculty-home.php'>Home</a></h4>
    </div>
    <div class='main-content'>
        <?php
            if (mysqli_num_rows($result) == 0) {
                echo '<h2 class="align-center" style="color: red;">No questions available!</h2>';
            } else {
                echo '<div class="row"><div class="question-table">
                        <form method="POST" action="random-qn-pdf.php">
                            <table>
                                <h2 class="align-center">All Questions</h2><br>
                                <tr>
                                    <th style="width: 120px;">Question ID</th>
                                    <th>Prompt</th>
                                    <th>Marks</th>
                                    


                                </tr>';

                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '<tr>
                            <td class="align-center">' . $row["question_ID"] . '</td>
                            <td>' . $row["prompt"] . '</td>
                            <td>' . $row["marks"] . '</td>
                            <td>' . $row["subject"] . '</td>

                            </tr>';
                }

                echo '</table>';

                // Display pagination links
                $totalQuestions = mysqli_num_rows(mysqli_query($db, 'SELECT question_ID FROM questions'));
                $totalPages = ceil($totalQuestions / $questionsPerPage);

                echo '<div class="pagination">';
                for ($i = 1; $i <= $totalPages; $i++) {
                    echo '<a href="?page=' . $i . '">' . $i . '</a>';
                }
                echo '</div>';

                echo '<p class="align-center"><input type="submit" name="generate_paper" value="Random QP"></p>
                    </form></div></div>';
            }
        ?>
    </div>
</body>
</html>
