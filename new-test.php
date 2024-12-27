



<?php
    include('faculty-session.php');
?>

<html>
    <head>
        <title>Tests - Faculty</title>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='style.css'>
        <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
    </head>
    <body>
        <header class='header'>
            <h2><a class='header-link' href='faculty-home.php'>Faculty Home</a></h2>
        </header>
        <div class='welcome'>
            <h4 class='align-right'>Welcome, <?php echo $login_session; ?> &emsp; <a href='logout.php'>Log Out</a> &emsp; <a href='faculty-home.php'>Home</a></h4>
        </div>
        <div class='main-content'>
            <div class='test-form'>
                <h2 class='align-center'>Create new test</h2>
                <hr>
                <form method='POST' action='create-test.php' >
                    <div id="tab">
                    <label for='test_name'>Test Name</label>
                    <input type='text' placeholder='Enter test name here...' name='test_name' id='test_name' autocomplete='off' required>
                    <br>
                    
                    <label for='question1'>Question 1 ID</label>
                    <select name='question1' id='question1' style="width:400px" onchange="updateSelectBoxes(this.value, [question2, question3])">

                    <?php
                         $sql = "SELECT question_ID, prompt FROM questions";
                         $result = mysqli_query($db, $sql);
                     
                         while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                             echo '<option value="'.$row['question_ID'].'">' . $row['question_ID'] . ' - ' . $row['prompt'] . '</option>';
                         }
                        ?>
                    </select>
                    <br>
                    <label for='question2'>Question 2 ID</label>
                    <select name='question2' id='question2' style="width:400px" onchange="updateSelectBoxes(this.value, [question1, question3])">
                        <?php
                            $sql = "SELECT question_ID, prompt FROM questions";
                            $result = mysqli_query($db, $sql);
                        
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                echo '<option value="'.$row['question_ID'].'">' . $row['question_ID'] . ' - ' . $row['prompt'] . '</option>';
                            }
                        ?>
                    </select>
                    <br>
                    <label for='question3'>Question 3 ID</label>
                    <select name='question3' id='question3' style="width:400px" onchange="updateSelectBoxes(this.value, [question1, question2])">
                        <?php
                            $sql = "SELECT question_ID, prompt FROM questions";
                            $result = mysqli_query($db, $sql);
                        
                            while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                                echo '<option value="'.$row['question_ID'].'">' . $row['question_ID'] . ' - ' . $row['prompt'] . '</option>';
                            }
                        ?>
                    </select>
                        </div>
                    <?php
// Place this PHP code at the top of your file or a separate PHP file

//include('faculty-session.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'print' && isset($_POST['test_name'])) {
        // Assuming you have a connection to the database already established
        // Your database connection code goes here...

        // Retrieve selected questions from the database using $_POST values
        $questions = array();
        for ($i = 1; $i <= 3; $i++) {
            $questionID = $_POST['question' . $i];
            $sql = "SELECT question_ID, prompt FROM questions WHERE question_ID = $questionID";
            $result = mysqli_query($db, $sql);
            $question = mysqli_fetch_assoc($result);
            $questions[] = $question;
        }

        // Generate the PDF
        require_once('tcpdf/tcpdf.php');

        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Your Name');
        $pdf->SetTitle($_POST['test_name']);

        $pdf->AddPage();
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Write(5, 'Test Name: ' . $_POST['test_name'] . "\n\n");

        foreach ($questions as $question) {
            $pdf->Write(5, 'Question ID: ' . $question['question_ID'] . "\n");
            $pdf->Write(5, 'Prompt: ' . $question['prompt'] . "\n\n");
        }

        $file_name = 'test_questions_' . date('Ymd_His') . '.pdf';
        $pdf->Output($file_name, 'D'); // 'D' forces download
        exit();
    }
}
?>

<!-- The rest of your HTML content -->

<form method='POST' action='create-test.php'>
    <!-- ... Other form elements ... -->
    <p class='align-center'>
       <!-- <button type='submit' name='action' value='create'>Create</button>
        <button type='submit' name='action' value='print'>Print</button>
    </p>
</form>
<!-- ... The rest of your HTML content ... -->
<head>
        <!-- ... (other head elements) ... -->
        <script>
            function updateSelectBoxes(selectedValue, selectBoxes) {
                for (var i = 0; i < selectBoxes.length; i++) {
                    var selectBox = selectBoxes[i];
                    for (var j = 0; j < selectBox.options.length; j++) {
                        if (selectBox.options[j].value === selectedValue) {
                            selectBox.options[j].disabled = true;
                        } else {
                            selectBox.options[j].disabled = false;
                        }
                    }
                }
            }
        </script>
        <!-- ... (rest of your head elements) ... -->
    </head>
</html>

                    <br>
                    <p class='align-center'> <button type='submit'>Create</button></p>
                    
                </form>
            </div>
        </div>
    </body>
</html>