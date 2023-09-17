<?php
require('fpdf/fpdf.php'); // Include the FPDF library

include('config.php');
session_start();

if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user_id = $_POST["user_id"];
    $category_id = $_POST["category_id"];

    // Fetch user details from the database
    $user_sql = "SELECT username, email, age, phone FROM users WHERE id = ?";
    $user_stmt = mysqli_prepare($conn, $user_sql);
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    mysqli_stmt_execute($user_stmt);
    mysqli_stmt_bind_result($user_stmt, $username, $email, $age, $phone);
    mysqli_stmt_fetch($user_stmt);
    mysqli_stmt_close($user_stmt);

    // Calculate the score
    $score = 0;
    $total_mcq = count($_POST["answer"]);

    foreach ($_POST["answer"] as $question_id => $selected_option) {
        // Retrieve the correct answer from the database
        $sql = "SELECT correct_answer FROM mcq_questions WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $question_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $correct_answer);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Check if the selected option is correct
        if ($selected_option == $correct_answer) {
            $score++;
        }
    }

    // Calculate percentage
    $percentage = ($score / $total_mcq) * 100;

    // Insert the test result into the database
    $sql = "INSERT INTO test_results (user_id, category_id, score, total_mcq) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "iiii", $user_id, $category_id, $score, $total_mcq);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Generate PDF certificate
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(40, 10, 'Test Certificate', 0, 1, 'C');
    $pdf->Cell(40, 10, '', 0, 1);
    $pdf->SetFont('Arial', '', 14);
    $pdf->Cell(40, 10, 'User ID: ' . $user_id, 0, 1);
    $pdf->Cell(40, 10, 'Username: ' . $username, 0, 1);
    $pdf->Cell(40, 10, 'Email: ' . $email, 0, 1);
    $pdf->Cell(40, 10, 'Age: ' . $age, 0, 1);
    $pdf->Cell(40, 10, 'Phone: ' . $phone, 0, 1);
    $pdf->Cell(40, 10, 'Category ID: ' . $category_id, 0, 1);
    $pdf->Cell(40, 10, 'Score: ' . $score . '/' . $total_mcq, 0, 1);
    $pdf->Cell(40, 10, 'Percentage: ' . $percentage . '%', 0, 1);
    $pdf->Cell(40, 10, 'Online Examination System', 0, 1); // Replace with your company name

    // Save the PDF file
    $file_name = 'certificate_' . $user_id . '_' . $category_id . '.pdf';
    $pdf->Output($file_name, 'F');

    // Provide a link to download the PDF certificate
    echo '<a href="' . $file_name . '" download>Download Certificate</a>';
}
?>
