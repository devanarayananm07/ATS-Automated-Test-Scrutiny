<?php
error_reporting(E_ERROR | E_PARSE);

require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

include('faculty-session.php');
include('config.php');

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['selected_questions'])) {
    $selectedQuestionIDs = $_POST['selected_questions'];

    if (!empty($selectedQuestionIDs)) {
        $questionIDs = implode(',', array_map('intval', $selectedQuestionIDs));
        $sql = "SELECT question_ID, prompt, marks FROM questions WHERE question_ID IN ($questionIDs)";
        $result = mysqli_query($db, $sql);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                // Start generating the PDF
                $data = '
                    <html>
                    <head>
                        <style>
                        h2 {
                            color: #333;
                            text-align: center;
                        }
                        
                            table {
                                width: 100%;
                                border-collapse: collapse;
                            }
                            th, td {
                                border: 0px solid black;
                                padding: 8px;
                                text-align: left;
                            }
                            td:nth-child(2) { /* Style for the prompt (question) column */
                                width: 70%; /* Adjust the width as needed */
                            }
                        </style>
                    </head>
                    <body>
                        <h2>Question Paper</h2>
                        <table>
                        <tr>
                            <th colspan="2">General Instructions</th>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul class="list-group">
                                    <li class="list-group-item">Students should bring the Identity Card and Hall Ticket during the
                                    examinations without fail. Students are not allowed to write examination if they
                                    do not bring both ID card and hall ticket.</li>
                                    <li class="list-group-item">Students should be present in the Examination Hall 15 minutes before the
                                    commencement of the examination</li>
                                    <li class="list-group-item">Students are not allowed to scribble on the Question Papers. Scribbling
                                    on the Question Paper is considered as an act of malpractice. Students are
                                    supposed to write only their Register No. on top of their question paper</li>
                                    <li class="list-group-item">Students are not supposed to carry mobile phones and smart watches to
                                    the campus on examination days</li>
                                    <li class="list-group-item">Students can keep their bags and books inside the examination hall near the
                                    blackboard. Students should not keep anything on the window pane</li> 
                                    <li class="list-group-item">Malpractice of any sort will be seriously dealt with.</li>
                                </ul>
                            <tr>
                                <th>Question No</th>
                                <th>Questions</th>
                                <th>Marks</th>
                            </tr>';
                
                $questionNumber = 1;

                while ($row = mysqli_fetch_assoc($result)) {
                    $data .= '
                            <tr>
                                <td>' . $questionNumber . '</td>
                                <td>' . $row['prompt'] . '</td>
                                <td>' . $row['marks'] . '</td>
                            </tr>';
                    $questionNumber++;
                }

                $data .= '
                        </table>
                    </body>
                    </html>';

                $dompdf = new Dompdf($options);
                $dompdf->loadHtml($data);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                // Output the PDF as a download file
                $pdfContent = $dompdf->output();
                file_put_contents('Generated_Question_Paper.pdf', $pdfContent);

                // Display the PDF in an iframe
                echo '<iframe src="Generated_Question_Paper.pdf" width="100%" height="600px"></iframe>';

                // Provide an option to download the PDF
                echo '<p><a href="Generated_Question_Paper.pdf" download>Download PDF</a></p>';
            } else {
                echo 'No questions selected.';
            }
        } else {
            echo 'Error executing the query: ' . mysqli_error($db);
        }
    } else {
        echo 'Please select questions.';
    }
} else {
    echo 'Invalid request method.';
}
?>
