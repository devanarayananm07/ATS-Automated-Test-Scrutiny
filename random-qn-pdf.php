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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['generate_paper'])) {
    // Randomly select 15 questions
    $sql = "SELECT question_ID, prompt, marks FROM questions ORDER BY RAND() LIMIT 15";
    $result = mysqli_query($db, $sql);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // Start generating the PDF
            $data = '
                <html>
                <head>
                    <style>
                        table {
                            width: 100%;
                            border-collapse: collapse;
                        }
                        th, td {
                            border: 0px solid black;
                            padding: 8px;
                            text-align: left;
                        }
                    </style>
                </head>
                <body>
                    <h2>Question Paper</h2>
                    <table>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>';

            $counter = 1; // Initialize the counter

            while ($row = mysqli_fetch_assoc($result)) {
                $data .= '
                        <tr>
                            <td class="align-center">' . $counter . '</td>
                            <td>' . $row['prompt'] . '</td>
                            <td>' . $row['marks'] . '</td>
                        </tr>';

                $counter++; // Increment the counter
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
            echo 'No questions available.';
        }
    } else {
        echo 'Error executing the query: ' . mysqli_error($db);
    }
} else {
    echo 'Invalid request method.';
}
?>
