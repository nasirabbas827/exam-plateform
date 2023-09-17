<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}


// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Retrieve data from the form
    $question = $_POST['question'];
    $category = $_POST['category'];
    $option1 = $_POST['option1'];
    $option2 = $_POST['option2'];
    $option3 = $_POST['option3'];
    $option4 = $_POST['option4'];
    $correct_answer = $_POST['correct_answer'];

    // SQL query to insert the MCQ into the database
    $sql = "INSERT INTO mcq_questions (question, category_id, option1, option2, option3, option4, correct_answer) 
            VALUES ('$question', $category, '$option1', '$option2', '$option3', '$option4', $correct_answer)";

    if ($conn->query($sql) === TRUE) {
        echo "MCQ question added successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add MCQ Question</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mb-5 ">
    <h1 class="mt-4">Add MCQ Question</h1>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div class="form-group">
            <label for="question">Question:</label>
            <input type="text" class="form-control" id="question" name="question" required>
        </div>

        <div class="form-group">
            <label for="category">Category:</label>
            <select class="form-control" id="category" name="category">
                <?php
                // Retrieve categories from the "categories" table
                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="option1">Option 1:</label>
            <input type="text" class="form-control" id="option1" name="option1" required>
        </div>

        <div class="form-group">
            <label for="option2">Option 2:</label>
            <input type="text" class="form-control" id="option2" name="option2" required>
        </div>

        <div class="form-group">
            <label for="option3">Option 3:</label>
            <input type="text" class="form-control" id="option3" name="option3" required>
        </div>

        <div class="form-group">
            <label for="option4">Option 4:</label>
            <input type="text" class="form-control" id="option4" name="option4" required>
        </div>

        <div class="form-group">
            <label for="correct_answer">Correct Answer:</label>
            <select class="form-control" id="correct_answer" name="correct_answer">
                <option value="1">Option 1</option>
                <option value="2">Option 2</option>
                <option value="3">Option 3</option>
                <option value="4">Option 4</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Add Question</button>
        <a href="view_questions.php" class="btn btn-secondary">View Questions</a>
    </form>
</div>

<!-- Include Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

