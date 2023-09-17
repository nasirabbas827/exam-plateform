<?php
include('config.php');

// Check if the user is logged in as an admin
session_start();
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Initialize variables to store user data
$id = $username = $email = $phone = $age = $category = '';
$username_err = $email_err = $phone_err = $age_err = $category_err = '';

// Check if an ID parameter is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch user data from the database
    $sql = "SELECT username, email, phone, age, selected_category FROM users WHERE id = $id";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $username = $row['username'];
        $email = $row['email'];
        $phone = $row['phone'];
        $age = $row['age'];
        $category = $row['selected_category'];
    } else {
        echo "User not found.";
        exit;
    }
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and update user details
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $category = $_POST['category'];

    // Update user details in the database
    $updateSql = "UPDATE users SET username = '$username', email = '$email', phone = '$phone', age = $age, selected_category = $category WHERE id = $id";

    if ($conn->query($updateSql) === TRUE) {
        echo "User details updated successfully.";
        header("Location: view_user.php");
        exit;
    } else {
        echo "Error updating user details: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <?php include('admin_navbar.php'); ?>

    <div class="container mt-4 mb-4">
        <h2>Edit User</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <input type="hidden" name="id" value="<?php echo $id; ?>">

            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $email; ?>">
            </div>

            <div class="form-group">
                <label>Phone Number</label>
                <input type="number" name="phone" class="form-control" value="<?php echo $phone; ?>">
            </div>

            <div class="form-group">
                <label>Age</label>
                <input type="number" name="age" class="form-control" value="<?php echo $age; ?>">
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category" class="form-control">
                    <?php
                    // Retrieve categories from the "categories" table
                    $categorySql = "SELECT id, name FROM categories";
                    $categoryResult = $conn->query($categorySql);

                    if ($categoryResult->num_rows > 0) {
                        while ($row = $categoryResult->fetch_assoc()) {
                            $categoryId = $row['id'];
                            $categoryName = $row['name'];
                            $selected = ($categoryId == $category) ? 'selected' : '';
                            echo "<option value='$categoryId' $selected>$categoryName</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Save Changes">
            </div>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
