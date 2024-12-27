<?php
   include('faculty-session.php');
?>

<html>
    <head>
        <title>Faculty Profile -Faculty</title>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='style.css'>
        <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
        <style>
            body, html {
                margin: 0;
                padding: 0;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th, td {
                border: 0px solid black;
                padding: 8px;
                text-align: left;
            }
        </style>
    </head>
    <body>
        <header class='header'>
            <h2><a class='header-link' href='faculty-home.php'>Faculty</a></h2>
        </header>
        <div class='welcome'>
            <h4 class='align-right'>Welcome, <?php echo $login_session; ?> &emsp; <a href='logout.php'>Log Out</a> &emsp; <a href='faculty-home.php'>Home</a></h4>
        </div>
        <div class='main-content'>
            <?php
                $sql = "SELECT faculty_ID, faculty_name, email, mobile FROM faculty WHERE faculty_ID = '$login_id'";
                $result = mysqli_query($db, $sql);

                echo '<div class="row"><div class="profile-table">
                        <table>
                        <h2 class="align-center">Your Profile</h2><br>
                            <tr>
                                <th style="width: 25%;">Faculty ID</th>
                                <th style="width: 25%;">Faculty Name</th>
                                <th style="width: 25%;">Email ID</th>
                                <th style="width: 25%;">Mobile</th>
                            </tr>';
                    
                while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
                    echo '<tr>
                            <td class="align-center">' . $row["faculty_ID"] . '</td>
                            <td class="align-center">' . $row["faculty_name"] . '</td>
                            <td class="align-center">' . $row["email"] . '</td>
                            <td class="align-center">' . $row["mobile"] . '</td>
                        </tr>';
                }

                echo '</table><p class="align-center"><a href="edit-faculty-profile.php">Edit Profile</a></p></div></div>';
            ?>
        </div>
    </body>
</html>