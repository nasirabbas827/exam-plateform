<?php
include('config.php');

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Fetch all user records from the database
$sql = "SELECT * FROM users";
$result = $conn->query($sql);

// Handle user deletion
if (isset($_GET['delete'])) {
    $userId = $_GET['delete'];

    // Perform user deletion in the database
    $deleteSql = "DELETE FROM users WHERE id = $userId";
    if ($conn->query($deleteSql) === TRUE) {
        // Redirect to the same page after deletion
        header("Location: view_user.php");
        exit;
    } else {
        echo "<p>Error deleting user: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container mt-4">
        <h2>User Management</h2>
        <a href="add_user.php" class="float-right mb-3 btn btn-primary">Add User</a>
        <table class="table mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Age</th>
                    <th>Category</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['age'] . "</td>";

                        // Retrieve category name based on selected_category
                        $categoryId = $row['selected_category'];
                        $categorySql = "SELECT name FROM categories WHERE id = $categoryId";
                        $categoryResult = $conn->query($categorySql);
                        $categoryName = ($categoryResult->num_rows > 0) ? $categoryResult->fetch_assoc()['name'] : 'N/A';

                        echo "<td>" . $categoryName . "</td>";
                        echo "<td>
                            <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-info btn-sm'>Edit</a>
                            <a href='view_user.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure you want to delete this user?\")'>Delete</a>
                        </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>No users found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
