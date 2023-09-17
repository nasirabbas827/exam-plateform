<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Function to get the list of categories
function getCategories() {
    global $conn;
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);
    $categories = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Check if the form is submitted for updating the question
if (isset($_POST['update_id'])) {
    $questionId = $_POST['update_id'];
    $newQuestion = $_POST['new_question'];
    $newCategory = $_POST['new_category'];
    $newOption1 = $_POST['new_option1'];
    $newOption2 = $_POST['new_option2'];
    $newOption3 = $_POST['new_option3'];
    $newOption4 = $_POST['new_option4'];
    $newCorrectAnswer = $_POST['new_correct_answer'];

    // Update the MCQ question in the database
    $sql = "UPDATE mcq_questions
            SET question = '$newQuestion', category_id = $newCategory,
                option1 = '$newOption1', option2 = '$newOption2',
                option3 = '$newOption3', option4 = '$newOption4',
                correct_answer = $newCorrectAnswer
            WHERE id = $questionId";

    if ($conn->query($sql) === TRUE) {
        echo "MCQ question updated successfully!";
        header("Location: view_questions.php");
        exit;
    } else {
        echo "Error updating question: " . $conn->error;
    }
}

// Get the question details to display in the form
if (isset($_GET['id'])) {
    $questionId = $_GET['id'];
    $sql = "SELECT * FROM mcq_questions WHERE id = $questionId";
    $result = $conn->query($sql);
    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $question = $row['question'];
        $category = $row['category_id'];
        $option1 = $row['option1'];
        $option2 = $row['option2'];
        $option3 = $row['option3'];
        $option4 = $row['option4'];
        $correctAnswer = $row['correct_answer'];
    } else {
        echo "Question not found.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Edit MCQ Question</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
<?php include('admin_navbar.php'); ?>

<div class="container mb-5">
    <h1 class="mt-4">Admin - Edit MCQ Question</h1>

    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <input type="hidden" name="update_id" value="<?php echo $questionId; ?>">

        <div class="form-group">
            <label for="new_question">Question:</label>
            <input type="text" class="form-control" id="new_question" name="new_question" value="<?php echo $question; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_category">Category:</label>
            <select class="form-control" id="new_category" name="new_category">
                <?php
                $categories = getCategories();
                foreach ($categories as $categoryRow) {
                    $categoryId = $categoryRow['id'];
                    $categoryName = $categoryRow['name'];
                    $selected = ($category == $categoryId) ? 'selected' : '';
                    echo "<option value='$categoryId' $selected>$categoryName</option>";
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="new_option1">Option 1:</label>
            <input type="text" class="form-control" id="new_option1" name="new_option1" value="<?php echo $option1; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_option2">Option 2:</label>
            <input type="text" class="form-control" id="new_option2" name="new_option2" value="<?php echo $option2; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_option3">Option 3:</label>
            <input type="text" class="form-control" id="new_option3" name="new_option3" value="<?php echo $option3; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_option4">Option 4:</label>
            <input type="text" class="form-control" id="new_option4" name="new_option4" value="<?php echo $option4; ?>" required>
        </div>

        <div class="form-group">
            <label for="new_correct_answer">Correct Answer:</label>
            <select class="form-control" id="new_correct_answer" name="new_correct_answer">
                <option value="1" <?php if ($correctAnswer == 1) echo 'selected'; ?>>Option 1</option>
                <option value="2" <?php if ($correctAnswer == 2) echo 'selected'; ?>>Option 2</option>
                <option value="3" <?php if ($correctAnswer == 3) echo 'selected'; ?>>Option 3</option>
                <option value="4" <?php if ($correctAnswer == 4) echo 'selected'; ?>>Option 4</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary" name="submit">Update Question</button>
    </form>
</div>

<!-- Include Bootstrap JS -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
