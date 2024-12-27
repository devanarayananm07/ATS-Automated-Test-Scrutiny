<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

//session_start();
include('student-session.php');
include('config.php');

$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$sql = "SELECT tests.test_name, scores.score FROM tests JOIN scores ON tests.test_ID=scores.test_ID WHERE student_ID='$login_id'";
$result = mysqli_query($db, $sql);

$sql2 = "SELECT student_name, student_ID, email, mobile FROM students WHERE student_id='$login_id'";
$result2 = mysqli_query($db, $sql2);
$row2 = mysqli_fetch_array($result2, MYSQLI_ASSOC);

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
                    <th>Test Name</th>
                    <th>Score</th>
                </tr>';
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $data .= '
                <tr>
                    <td>' . $row["test_name"] . '</td>
                    <td>' . $row["score"] . ' / 30.00</td>
                </tr>';
    }
    $data .= '
            </table>
            <h4>Student Details</h4>
            <p>Name: ' . $row2["student_name"] . '</p>
            <p>Student ID: ' . $row2["student_ID"] . '</p>
            <p>Email: ' . $row2["email"] . '</p>
            <p>Phone: ' . $row2["mobile"] . '</p>
        </body>
        </html>';

    $dompdf = new Dompdf($options);
    $dompdf->loadHtml($data);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    // Output the PDF as a download file
    $dompdf->stream("results_summary.pdf", array("Attachment" => false));
}
?>
