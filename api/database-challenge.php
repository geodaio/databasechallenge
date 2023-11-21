<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <title>Database Challenge</title>
    <style>
        .container {
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
            border: 2px solid black;
            padding: 5px;
        }
        
        label,
        input {
            display: block;
            margin-top: 5px;
        }

        table,
        th,
        td {
            margin: 20px auto;
            padding: 5px;
            border: 1px solid black;
            border-collapse: collapse;
        }

    </style>
</head>

<body>

    <?php    
        // $view is false until we view our table with the "View Table" button
        // $message is empty until we have a message to output
        $view = false;
        $message = "";
    
        if(isset($_POST['submit'])) { // Has our form been submitted?
            
            include 'pwd.php'; // Include our password file

            // Create new connection through mysqli using the four pieces of credentials
            $conn = new mysqli($servername, $username, $password, $db);

            // Check connection and quit if it doesn't work
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Use switch to see which button was submitted (same as if/else if/else)
            switch($_POST['submit']) {
                    
                // Used for creating our table
                // Our table will have an id for the primary key, and firstname, lastname, and email columns
                case 'create': 
                    $sql = "CREATE TABLE customers (
                    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,  
                    firstname VARCHAR(30) NOT NULL, 
                    lastname VARCHAR(30) NOT NULL,
                    email VARCHAR(50) NOT NULL,
                    age INT(3) NOT NULL, 
                    favFood VARCHAR(30) NOT NULL, 
                    designation VARCHAR(7) NOT NULL);";
                    $message = "Table created successfully";
                    break;

                // This is where our insert query is created
                case 'insert':                    
                    $sql = "INSERT INTO customers (firstname, lastname, email, age, favFood, designation) VALUES 
                    ('Chris', 'Velez', 'cvelez@albany.edu', '30', 'Lasagna', 'Citizen'), 
                    ('Tony', 'Stark', 'tstark@mail.com', '40', 'Burgers', 'Hero'), 
                    ('Steve', 'Rogers', 'srogers@mail.com', '70', 'Apple Pie', 'Hero'),
                    ('Anakin', 'Skywalker', 'askywalkter@mail.com', '45', 'Cookies', 'Villain'),
                    ('Rafael', 'Turtle', 'rafael-ninja@mail.com', '20', 'Pizza', 'Hero'), 
                    ('Leonardo', 'Turtle', 'leonardo-ninja@mail.com', '20', 'Pizza', 'Hero'),
                    ('Troy', 'Polamalu', 'tpolamalu@mail.com', '40', 'Chicken', 'Citizen'),
                    ('Galactus', 'Planeteater', 'galactus@mail.com', '2000', 'Planets', 'Villain')";
                    $message = "Customer added successfully.";
                    break;

                // Used for dropping our table
                case 'delete':
                    $sql = "DROP TABLE IF EXISTS customers";
                    $message = "Table dropped succesfully";
                    break;
                    
                // Used for viewing our table after some inserts have been done
                case 'view': 
                    $sql = "SELECT * FROM customers";
                    $message = "<br>Your results:";
                    $view = true;
                    break;

                // Defaults to nothing if we don't get an appropriate value
                default: 
                    break;
            }
            
            // Set our query results on the database to a variable
            $result = $conn->query($sql);

            // If the create table query we ran on the database is bad, let the user know.
            if (!$result) {
                $message =  "Error: " . $sql . "<br>" . $conn->error;
            }

            // Close connection - ALWAYS DO THIS
            $conn->close();
        }
    ?>

    <div class="container">
        <form name="myForm" action="" method="post">
        <p>Use the buttons below to create our Customers Table, populate it with data, view it, or delete it:</p>
        <button type="submit" name="submit" value="create">Create Table</button>

        <button type="submit" name="submit" value="insert">Populate Table</button>  
        
        <button type="submit" name="submit" value="view">View Table</button>   
        
        <button type="submit" name="submit" value="delete">Delete Table</button>
        </form>

        <?php 

            echo "$message";

            // If view is true, we output our content into a table
            if($view) {
                echo "<table>";
                echo "<tr><th>IDs</th><th>First Name</th><th>Last Name</th><th>Email</th><th>Age</th><th>Favorite Food</th><th>Designation</th></tr>";
                while($row = $result->fetch_assoc()) {
                    echo "<tr><td>" . $row["id"] . "</td>";
                    echo "<td>" . $row["firstname"] . "</td>";
                    echo "<td>" . $row["lastname"] . "</td>";
                    echo "<td>" . $row["email"] . "</td>";
                    echo "<td>" . $row["age"] . "</td>";
                    echo "<td>" . $row["favFood"] . "</td>";
                    echo "<td>" . $row["designation"] . "</td></tr>";
                }
                echo "</table>";  
            }
        ?>
    </div>
    
</body>

</html>
