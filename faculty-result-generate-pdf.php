<?php
// Set error reporting to exclude warnings
error_reporting(E_ERROR | E_PARSE);
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//session_start();
include('faculty-session.php');
include('config.php');
error_reporting(0);
error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);


$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$sql = "SELECT tests.test_ID, tests.test_name, students.student_ID, students.student_name, scores.score FROM tests JOIN scores ON tests.test_ID=scores.test_ID JOIN students on scores.student_ID = students.student_ID";
$result = mysqli_query($db, $sql);

if (mysqli_num_rows($result) == 0) {
    echo '<h2 class="align-center" style="color: red;">No results available!</h2>';
} else {
    $data = '
        <html>
        <head>
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
            <h2>Available Results</h2>
            <table>
                <tr>
                    <th>Test Id</th>
                    <th>Test Name</th>
                    <th>Student ID</th>
                    <th>Student Name</th>
                    <th>Score</th>
                </tr>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data .= '
                <tr>
                    <td>' . $row["test_ID"] . '</td>
                    <td>' . $row["test_name"] . '</td>
                    <td>' . $row["student_ID"] . '</td>
                    <td>' . $row["student_name"] . '</td>
                    <td>' . $row["score"] . ' / 30.00</td>
                </tr>';
    }
    $data .= '
            </table>
            <h4>Faculty Details</h4>
            <p>Faculty Name: ' . $login_session . '</p>
            <p>Faculty ID: ' . $login_id . '</p>
            <p>Email: ' . $faculty_email . '</p>
        </body>
        </html>';

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($data);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Encode the PDF content in base64
    $pdfContent = base64_encode($dompdf->output());

    // Output the iframe with the data URI
    echo '<iframe src="data:application/pdf;base64,' . $pdfContent . '" width="100%" height="500px"></iframe>';

    // Provide a download link
    echo '<form id="downloadForm" action="download_faculty_pdf.php" method="post" style="display: none;">
            <input type="hidden" name="pdfContent" value="' . $pdfContent . '">
          </form>';
    echo '<p><a href="#" onclick="document.getElementById(\'downloadForm\').submit();">Download PDF</a></p>';
}
?>
