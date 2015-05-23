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
        <h1>Pok&eacute;mon!</h1>

        <?php

        $usernameSent = isset($_POST["username"]);
        $username = "";

        if ($usernameSent)
        {
            $username = $_POST['username'];
        }
        if (!$usernameSent || $username == "")
        {
            echo "<form id=\"login\" action=\"login.php\" method=\"POST\">";
            echo "<p>Username <input type=\"input\" name=\"username\" id=\"username\"/></p>";
            echo "<p>Password <input type=\"password\" name=\"password\" id=\"password\"/></p>";
            echo "<button type=\"submit\">Calculate</button>";
            echo "</form>";
        }
        else
        {
            try
            {
                $user = 'php';
                $password = 'passw0rd';
                $db = new PDO("mysql:host=localhost; dbname=type_multiplier",$user,$password); 
            }
            catch (PDOException $ex)
            {
                echo 'Error!: ' . $ex->getMessage();
                die();
            }

            $usernameExists = false;

            foreach ($db->query('SELECT * from users') as $row)
            {
                if ($row['username'] == $username)
                {
                    $usernameExists = true;
                    break;
                }
            }

            if ($usernameExists)
            {
                $password = $_POST['password'];
                foreach ($db->query("SELECT password from users where username = '$username' ") as $row)
                {
                    if ($row['password'] == $password)
                    {
                        $_SESSION['username'] = $username;
                        header('Location: typeCalculator1.php');  
                    }
                    else
                    {
                        echo "<p>Login information was incorrect. Please try again.</p>";
                    }
                }
            }
            else
            {
                echo "<p>Login Information was incorrect. Please try again.</p>";
            }
        }
        ?>

    </body>
</html>