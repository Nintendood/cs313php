<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;mon Type Modifier</title>
        <script type="text/javascript" src="login.js"></script>
        <link rel="stylesheet" type="text/css" href="calculator.css">
    </head>
    <!--need php_readonly somewhere -->
    <body>
        <div class="signup">      
        <h1><img src="pokemon.png" class="logo"/></h1>
        <h2>Type Modifier Calculator</h2>

        <?php

        $usernameSent = isset($_POST["username"]);
        $username = "";

        if ($usernameSent)
        {
            $username = $_POST['username'];
        }
        if (!$usernameSent || $username == "")
        {
            echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
            echo "<p>Username <input type=\"input\" name=\"username\" id=\"username\"/></p>";
            echo "<p>Password <input type=\"password\" name=\"password\" id=\"password\"/></p>";
            echo "<button type=\"signup\" onclick=\"addUserData\">Sign Up</button>";
            echo "</form>";
        }
        else
        {
            try
        {

            $dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
            $dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
            $dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
            $dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');
            $db = new PDO("mysql:host=$dbHost:$dbPort; dbname=type_multiplier",$dbUser,$dbPassword); 

            //echo "host:$dbHost:$dbPort dbName:$dbName user:$dbUser password:$dbPassword<br />\n";

            //$user = 'php';
            //$password = 'passw0rd';
            //$db = new PDO("mysql:host=localhost; dbname=type_multiplier",$user,$password); 
        }
        catch (PDOException $ex)
        {
            echo '<p>Error!: ' . $ex->getMessage() . "</p>";
            die();
        }

            $usernameExists = false;

            foreach ($db->query("SELECT * from users WHERE username='$username'") as $row)
            {
                if ($row['username'] == $username)
                {
                    $usernameExists = true;
                    break;
                }
            }

            if ($usernameExists)
            {
                echo "<p>There is already a user by that name. Please choose a different username.</p>";
                echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
                echo "<p>Username <input type=\"input\" name=\"username\" id=\"username\"/></p>";
                echo "<p>Password <input type=\"password\" name=\"password\" id=\"password\"/></p>";
                echo "<button type=\"signup\">Sign Up</button>";
                echo "</form>";
            }
            else
            {
                $username = $_POST['username'];
                $password = $_POST['password'];
                $qry = $db->prepare("INSERT INTO users (username, password) VALUES (:username, :password);");
                $qry->bindValue(':username', $username);
                $qry->bindValue(':password', $password);
                $qry->execute();

                $_SESSION['username'] = $username;
                header('Location: typeCalculator2.php');  
            }
        }
        ?>
        </div>
    </body>
</html>