<?php
session_start();
include('config.php');

// Check if the user is logged in as an admin
if (!isset($_SESSION["usertype"]) || $_SESSION["usertype"] !== "admin") {
    header("Location: admin_login.php");
    exit;
}

// Function to get the list of MCQ questions
function getMCQQuestions() {
    global $conn;
    $sql = "SELECT mcq.id, mcq.question, mcq.category_id, cat.name AS category_name, mcq.option1, mcq.option2, mcq.option3, mcq.option4, mcq.correct_answer
            FROM mcq_questions mcq
            JOIN categories cat ON mcq.category_id = cat.id";
    $result = $conn->query($sql);
    $questions = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $questions[] = $row;
        }
    }
    return $questions;
}

// Handle delete action
if (isset($_POST['delete_id'])) {
    $questionId = $_POST['delete_id'];
    $sql = "DELETE FROM mcq_questions WHERE id = $questionId";
    if ($conn->query($sql) === TRUE) {
        // Refresh the page after deletion
        header("Location: view_questions.php");
        exit;
    } else {
        echo "Error deleting question: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Manage MCQ Questions</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php include('admin_navbar.php'); ?>

    <div class="container">
    <h1>Admin - Manage MCQ Questions</h1>
    <a href="add_questions.php" class="float-right mb-3 btn btn-primary">Add Questions</a>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Question</th>
                <th>Category</th>
                <th>Options</th>
                <th>Correct Answer</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $questions = getMCQQuestions();
            foreach ($questions as $question) {
                echo "<tr>";
                echo "<td>" . $question['id'] . "</td>";
                echo "<td>" . $question['question'] . "</td>";
                echo "<td>" . $question['category_name'] . "</td>";
                echo "<td>";
                echo "1. " . $question['option1'] . "<br>";
                echo "2. " . $question['option2'] . "<br>";
                echo "3. " . $question['option3'] . "<br>";
                echo "4. " . $question['option4'];
                echo "</td>";
                echo "<td>" . $question['correct_answer'] . "</td>";
                echo "<td>";
                echo "<a href='edit_questions.php?id=" . $question['id'] . "' class='btn btn-primary'>Edit</a> ";
                echo "<form method='post' style='display: inline;'>
                        <input type='hidden' name='delete_id' value='" . $question['id'] . "'>
                        <button type='submit' class='btn btn-danger' onclick='return confirm(\"Are you sure you want to delete this question?\")'>Delete</button>
                      </form>";
                echo "</td>";
                echo "</tr>";
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
