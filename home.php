<?php
include('config.php');

session_start();

// Check if user is logged in, if not, redirect to login page
if (!isset($_SESSION["id"]) || empty($_SESSION["id"])) {
    header("location: index.php");
    exit;
}

// Get the user ID from the session
$user_id = $_SESSION["id"];

// Fetch user details from the database
$sql = "SELECT id, username, email, age, selected_category FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $fetched_id, $username, $email, $age, $selected_category);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Fetch user's selected category name
$category_name = "N/A";
if ($selected_category) {
    $sql = "SELECT name FROM categories WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $selected_category);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $category_name);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Check if the user has a selected category
if ($selected_category) {
    // Check if the user has already attempted the test
    $test_result_sql = "SELECT score, total_mcq FROM test_results WHERE user_id = ? AND category_id = ?";
    $test_result_stmt = mysqli_prepare($conn, $test_result_sql);
    mysqli_stmt_bind_param($test_result_stmt, "ii", $user_id, $selected_category);
    mysqli_stmt_execute($test_result_stmt);
    mysqli_stmt_bind_result($test_result_stmt, $score, $total_mcq);
    $test_result_exists = mysqli_stmt_fetch($test_result_stmt);
    mysqli_stmt_close($test_result_stmt);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome, <?php echo $username; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <?php include('navbar.php'); ?>

    <div class="container mt-5">
        <h2>Welcome, <?php echo $username; ?>!</h2>

        <?php if ($selected_category) : ?>
            <h3>Selected Test Category: <?php echo $category_name; ?></h3>
            <?php if ($test_result_exists) : ?>
                <h4>Your Test Result:</h4>
                <h1>Score: <?php echo $score; ?>/<?php echo $total_mcq; ?></h1>
            <?php else : ?>
                <form method="post" action="save_test_result.php">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="category_id" value="<?php echo $selected_category; ?>">
                    <?php
                    // Fetch MCQ questions for the selected category
                    $sql = "SELECT id, question, option1, option2, option3, option4 FROM mcq_questions WHERE category_id = ?";
                    $stmt = mysqli_prepare($conn, $sql);
                    mysqli_stmt_bind_param($stmt, "i", $selected_category);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);
                    mysqli_stmt_close($stmt);
                    ?>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <div class="card mt-3">
                            <div class="card-body">
                                <p><?php echo $row['question']; ?></p>
                                <label class="radio-inline">
                                    <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="1"> <?php echo $row['option1']; ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="2"> <?php echo $row['option2']; ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="3"> <?php echo $row['option3']; ?>
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="answer[<?php echo $row['id']; ?>]" value="4"> <?php echo $row['option4']; ?>
                                </label>
                            </div>
                        </div>
                    <?php endwhile; ?>
                    <button type="submit" class="btn btn-primary mt-3">Submit Test</button>
                </form>
            <?php endif; ?>
        <?php else : ?>
            <p>No test category selected. Please select a test category from your profile.</p>
        <?php endif; ?>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
