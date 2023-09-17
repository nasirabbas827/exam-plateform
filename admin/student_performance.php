<?php
include('config.php');

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch the list of students from the database
$sql = "SELECT id, username FROM users";
$result = $conn->query($sql);

// Initialize variables for selected student's performance
$selectedStudentId = null;
$testResults = [];

// Handle the selection of a student
if (isset($_POST['student_id'])) {
    $selectedStudentId = $_POST['student_id'];

    // Query the database for test results of the selected student
    $testResultsSql = "SELECT tr.id, tr.category_id, tr.score, tr.total_mcq, tr.timestamp, c.name AS category_name
                      FROM test_results tr
                      INNER JOIN categories c ON tr.category_id = c.id
                      WHERE tr.user_id = ?";
    $stmt = mysqli_prepare($conn, $testResultsSql);
    mysqli_stmt_bind_param($stmt, "i", $selectedStudentId);
    mysqli_stmt_execute($stmt);
    $testResults = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Performance Report</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container mt-4">
        <h2>Student Performance Report</h2>

        <form method="post">
            <div class="form-group">
                <label>Select Student:</label>
                <select name="student_id" class="form-control">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['username']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">View Performance</button>
        </form>

        <?php if (!empty($testResults)) : ?>
            <h3 class="mt-4">Test Results for Selected Student:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Test Category</th>
                        <th>Category Name</th>
                        <th>Score</th>
                        <th>Total MCQ</th>
                        <th>Test Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($testResults)) : ?>
                        <tr>
                            <td><?php echo $row['category_id']; ?></td>
                            <td><?php echo $row['category_name']; ?></td>
                            <td><?php echo $row['score']; ?></td>
                            <td><?php echo $row['total_mcq']; ?></td>
                            <td><?php echo $row['timestamp']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
