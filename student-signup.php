<!DOCTYPE html>
<html>

<head>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <title>Sign Up</title>
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f2f2f2;
            text-align: center;
            padding: 20px;
        }

        h2 {
            color: #333;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
    <script>
        function validateForm() {
            var student_id = document.forms["myForm"]["student_id"].value;
            var name = document.forms["myForm"]["name"].value;
            var email = document.forms["myForm"]["email"].value;
            var phone = document.forms["myForm"]["phone"].value;
            var password = document.forms["myForm"]["password"].value;

            if (student_id == "" || name == "" || email == "" || phone == "" || password == "") {
                alert("All fields must be filled out");
                return false;
            }
            if (!email.includes('@')) {
                alert("Invalid email format");
                return false;
            }
            if (!phone.match(/^\d{10}$/)) {
                alert("Invalid phone number");
                return false;
            }
            if (!password.match(/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/)) {
                alert("Password must contain at least 8 characters, including one numeric digit, one uppercase, and one lowercase letter");
                return false;
            }
        }
    </script>
</head>

<body>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "webaes";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Check if the form data is not empty
        if (!empty($_POST["student_id"]) && !empty($_POST["name"]) && !empty($_POST["email"]) && !empty($_POST["phone"]) && !empty($_POST["password"])) {
            // Sanitize the form data
            $student_id = mysqli_real_escape_string($conn, $_POST["student_id"]);
            $name = mysqli_real_escape_string($conn, $_POST["name"]);
            $email = mysqli_real_escape_string($conn, $_POST["email"]);
            $phone = mysqli_real_escape_string($conn, $_POST["phone"]);
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT); // Hash the password

            // Insert data into the database
            $sql = "INSERT INTO students (student_ID, student_name, email, mobile, password) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssss", $student_id, $name, $email, $phone, $password);
            $stmt->execute();
            $stmt->close();

            // Redirect to the login page
            header("Location: student-login.php");
            exit();
        } else {
            echo "Error: All fields are required";
        }
    }

    $conn->close();
    ?>

    <h2>Student Sign Up</h2>

    <form name="myForm" action="" onsubmit="return validateForm()" method="post">
        <label for="student_id">Student ID:</label><br>
        <input type="text" id="student_id" name="student_id" placeholder="Enter your Student ID" required><br>
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" placeholder="Enter your Name" required><br>
        <label for="email">Email:</label><br>
        <input type="text" id="email" name="email" placeholder="Enter your Email" required><br>
        <label for="phone">Mobile:</label><br>
        <input type="text" id="phone" name="phone" placeholder="Enter your Mobile Number" required><br>
        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter your Password" required><br>
        <input type="submit" value="Submit">
    </form>
</body>

</html>
