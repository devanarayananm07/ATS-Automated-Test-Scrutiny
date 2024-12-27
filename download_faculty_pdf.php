<?php
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="faculty_results_summary.pdf"');

// Decode the base64-encoded PDF content
echo base64_decode($_POST['pdfContent']);
?>
