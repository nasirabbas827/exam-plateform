<?php
include('config.php');

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch the list of test categories from the database
$sql = "SELECT id, name FROM categories";
$result = $conn->query($sql);

// Initialize variables for selected category's performance
$selectedCategoryId = null;
$categoryResults = [];

// Handle the selection of a category
if (isset($_POST['category_id'])) {
    $selectedCategoryId = $_POST['category_id'];

    // Query the database for test results of the selected category
    $categoryResultsSql = "SELECT tr.user_id, u.username, tr.score, tr.total_mcq, tr.timestamp
                           FROM test_results tr
                           INNER JOIN users u ON tr.user_id = u.id
                           WHERE tr.category_id = ?";
    $stmt = mysqli_prepare($conn, $categoryResultsSql);
    mysqli_stmt_bind_param($stmt, "i", $selectedCategoryId);
    mysqli_stmt_execute($stmt);
    $categoryResults = mysqli_stmt_get_result($stmt);
    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Category Performance Report</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container mt-4">
        <h2>Category Performance Report</h2>

        <form method="post">
            <div class="form-group">
                <label>Select Category:</label>
                <select name="category_id" class="form-control">
                    <?php while ($row = $result->fetch_assoc()) : ?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">View Performance</button>
        </form>

        <?php if (!empty($categoryResults)) : ?>
            <h3 class="mt-4">Test Results for Selected Category:</h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Score</th>
                        <th>Total MCQ</th>
                        <th>Test Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($categoryResults)) : ?>
                        <tr>
                            <td><?php echo $row['username']; ?></td>
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
