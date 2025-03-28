<?php
    include('faculty-session.php');
?>

<html>
    <head>
        <title>Remove Questions -Faculty</title>
        <meta charset='UTF-8'>
        <link rel='stylesheet' type='text/css' href='style.css'>
        <link href='https://fonts.googleapis.com/css?family=Open Sans' rel='stylesheet'>
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
                $sql = 'SELECT test_ID, test_name FROM tests';
                $result = mysqli_query($db, $sql);

                if(mysqli_num_rows($result)==0){
                    echo '<h2 class="align-center" style="color: red;">No tests available!</h2>';
                }
                else{
                    echo '<div class="row"><div class="tests-table">
                    <form method="POST" action="delete-test.php">
                        <table>
                        <h2 class="align-center">Remove Tests</h2><br>
                            <tr>
                                <th style="width: 50%;">Test ID</th>
                                <th style="width: 50%;">Test Name</th>
                                <th>Remove</th>
                            </tr>';
                        while($row = mysqli_fetch_assoc($result)){
                            echo "<tr>";
                            echo "<td>".$row['test_ID']."</td>";
                            echo "<td>".$row['test_name']."</td>";
                            echo "<td> <input type='checkbox' name='checkbox[]' value='".$row["test_ID"]."'</td>";
                            echo "</tr>";
                        }

                        echo '</table>
                        <p class="align-center"><input type="submit" name="remove" value="Remove"></p>
                    </form></div></div>';
                }
            ?>
        </div>
    </body>
</html>