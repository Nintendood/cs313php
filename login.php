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
        <div class="login">      
        <h1><img src="pokemon.png" class="logo"/></h1>
        <h2>Type Modifier Calculator</h2>

        <?php
        error_reporting(E_ALL);
        ini_set("display_errors", 1);

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
            echo "<button type=\"submit\">Log In</button> ";
            echo "</form>";
            echo "<form id=\"signup\" action=\"signup.php\" method=\"POST\">";
            echo "<button type=\"submit\" onclick=\"addUserData\">Sign Up</button>";
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
                        header('Location: typeCalculator2.php');  
                    }
                    else
                    {
                        echo "<p>Login information was incorrect. Please try again.</p>";
                        echo "<form id=\"login\" action=\"login.php\" method=\"POST\">";
                        echo "<p>Username <input type=\"input\" name=\"username\" id=\"username\"/></p>";
                        echo "<p>Password <input type=\"password\" name=\"password\" id=\"password\"/></p>";
                        echo "<button type=\"submit\">Log In</button> ";
                        echo "<button type=\"signup\">Sign Up</button>";
                        echo "</form>";
                    }
                }
            }
            else
            {
                echo "<p>Login Information was incorrect. Please try again.</p>";
                echo "<form id=\"login\" action=\"login.php\" method=\"POST\">";
                echo "<p>Username <input type=\"input\" name=\"username\" id=\"username\"/></p>";
                echo "<p>Password <input type=\"password\" name=\"password\" id=\"password\"/></p>";
                echo "<button type=\"submit\">Log In</button> ";
                echo "<button type=\"signup\">Sign Up</button>";
                echo "</form>";
            }
        }
        ?>
        </div>
    </body>
</html>