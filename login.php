<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;mon Type Modifier</title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="calculator.css">
    </head>
    <!--need php_readonly somewhere -->
    <body>
        <div class="login">      
        <h1><img src="pokemon.png" class="logo"/></h1>
        <h3>Type Modifier Calculator</h3>

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
            echo "<div class=\"col-xs-6\">";
            echo "<p>Username <input class=\"form-control \" type=\"input\" name=\"username\" id=\"username\"/></p>";
            echo "</div>";
            echo "<div class=\"col-xs-6\">";
            echo "<p>Password <input class=\"form-control\" type=\"password\" name=\"password\" id=\"password\"/></p>";
            echo "</div>";
            echo "</br><button class=\"btn btn-primary btn-block\" type=\"submit\">Log In</button> ";
            echo "</br></form>";
            echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
            echo "<button class=\"btn btn-info btn-block\" type=\"submit\">Sign Up</button>";
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
            }
            catch (PDOException $ex)
            {
                echo '<p>Error!: ' . $ex->getMessage() . "</p>";
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
                    if (password_verify($password, $row['password']))
                    {
                        $_SESSION['username'] = $username;
                        header('Location: typeCalculator2.php');  
                    }
                    else
                    {
                        echo "<p>Login information was incorrect. Please try again.</p>";
                        echo "<form id=\"login\" action=\"login.php\" method=\"POST\">";
                        echo "<div class=\"col-xs-6\">";
                        echo "<p>Username <input class=\"form-control \" type=\"input\" name=\"username\" id=\"username\"/></p>";
                        echo "</div>";
                        echo "<div class=\"col-xs-6\">";
                        echo "<p>Password <input class=\"form-control\" type=\"password\" name=\"password\" id=\"password\"/></p>";
                        echo "</div>";
                        echo "</br><button class=\"btn btn-primary btn-block\" type=\"submit\">Log In</button> ";
                        echo "</br></form>";
                        echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
                        echo "<button class=\"btn btn-info btn-block\" type=\"submit\" onclick=\"addUserData\">Sign Up</button>";
                        echo "</form>";
                    }
                }
            }
            else
            {
                echo "<p>Login Information was incorrect. Please try again.</p>";
                echo "<form id=\"login\" action=\"login.php\" method=\"POST\">";
                echo "<div class=\"col-xs-6\">";
                echo "<p>Username <input class=\"form-control \" type=\"input\" name=\"username\" id=\"username\"/></p>";
                echo "</div>";
                echo "<div class=\"col-xs-6\">";
                echo "<p>Password <input class=\"form-control\" type=\"password\" name=\"password\" id=\"password\"/></p>";
                echo "</div>";
                echo "</br><button class=\"btn btn-primary btn-block\" type=\"submit\">Log In</button> ";
                echo "</br></form>";
                echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
                echo "<button class=\"btn btn-info btn-block\" type=\"submit\" onclick=\"addUserData\">Sign Up</button>";
                echo "</form>";
            }
        }
        ?>
        </div>
    </body>
</html>