<?php
    include('faculty-session.php');
?>

<html>
    <head>
        <title>Generate Question Paper -Faculty</title>
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
            <?php
                $sql = 'SELECT question_ID, prompt, expected_answer FROM questions';
                $result = mysqli_query($db, $sql);

                if(mysqli_num_rows($result)==0){
                    echo '<h2 class="align-center" style="color: red;">No questions available!</h2>';
                }
                else{
                    echo '<div class="row"><div class="question-table">
                    <form action="" method="post">
                    <label for="question_paper_name">Question Paper Name:</label>
                    <input type="text" id="question_paper_name" name="question_paper_name" required>
                    <br>
                    <table>
                        <tr>
                            <th>Select</th>
                            <th>Question ID</th>
                            <th>Prompt</th>
                            <th>Marks</th>
                        </tr>
                            <tr>
                                <th style="width: 120px;">Question ID</th>
                                <th>Prompt</th>
                                <th>Expected Answer</th>
                                <th>Select</th>
                            </tr>';
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row['question_ID']."</td>";
                            echo "<td>".$row['prompt']."</td>";
                            echo "<td>".$row['expected_answer']."</td>";
                            echo "<td> <input type='checkbox' name='checkbox[]' value='".$row["question_ID"]."'</td>";
                            echo "</tr>";
                        }

                        echo '</table>
                        
                    </form></div></div>';
                }
            ?>
            <form method="POST" action="generate-question-pdf.php">
    <input type="text" name="question_paper_name" placeholder="Enter question paper name" required>
    <!-- ... (your existing form fields) ... -->
    <input type="submit" name="generate_paper" value="Generate PDF">
</form>

        </div>
    </body>
</html>