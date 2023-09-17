</footer>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Exam Platform</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        .jumbotron-custom {
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3)), url('./images/bg.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: #fff;
            text-align: center;
            padding: 100px 0;
            height: 400px;
        }
    </style>
</head>

<body>
    <?php
    include('navbar.php');
    ?>
    <div class="jumbotron jumbotron-custom">
        <h1>Welcome to Online Exam Platform</h1>
        <p>Your gateway to convenient and efficient online exams.</p>
        <a class="btn btn-primary btn-lg" href="login.php">Get Started</a>
    </div>

    
    <!-- About Us Section -->
    <div class="container mt-4">
                <!-- Test Categories Section -->
                <section id="test-categories">
            <h2>Test Categories</h2>
            <div class="row">
                <?php
                include('config.php');

                // Query to fetch test categories
                $sql = "SELECT id, name FROM categories";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $category_id = $row["id"];
                        $category_name = $row["name"];

                        echo '<div class="col-md-3 mb-4">';
                        echo '<div class="card">';
                        echo '<div class="card-body">';
                        echo '<h5 class="card-title">' . $category_name . '</h5>';
                echo '<a href="login.php' . '" class="mt-3 btn btn-primary">Exam Now</a>'; // Add Exam Now button
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }
                }

                // Close the database connection
                $conn->close();
                ?>
            </div>
        </section>
        <section id="about-us">
            <h2>About Us</h2>
            <p>Welcome to the Online Exam Platform, where we make taking exams easy and accessible. Our platform provides a wide range of test categories to suit your educational and professional needs. We are committed to delivering a secure and user-friendly experience for all our users.</p>
        </section>

    </div>

        <footer class="mt-5 py-3 bg-light">
            <div class="container text-center">
                <p>&copy; 2023 Online Examination Plateform. All rights reserved.</p>
            </div>
            <!-- Include Bootstrap JS -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>