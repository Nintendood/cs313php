<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;mon Type Modifier</title>
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="typeCalculator.js"></script>
        <link rel="stylesheet" type="text/css" href="calculator.css">
    </head>
    <!--need php_readonly somewhere -->
    <body>        
        <h1>Pok&eacute;mon!</h1>
        <?php

        try
        {

        	$dbHost = getenv('OPENSHIFT_MYSQL_DB_HOST');
			$dbPort = getenv('OPENSHIFT_MYSQL_DB_PORT');
			$dbUser = getenv('OPENSHIFT_MYSQL_DB_USERNAME');
			$dbPassword = getenv('OPENSHIFT_MYSQL_DB_PASSWORD');

            $user = 'php';
            $password = 'passw0rd';
            $db = new PDO("mysql:host=$dbHost:$dbPort; dbname=type_multiplier",$dbUser,$dbPassword); 

            //$db = new PDO("mysql:host=localhost; dbname=type_multiplier",$user,$password); 
        }
        catch (PDOException $ex)
        {
            echo 'Error!: ' . $ex->getMessage();
            die();
        }

        $username = $_SESSION['username'];
        echo "<p>User: " . $username . "</p>";

        $qry = "SELECT id FROM users WHERE username = '$username'";

        $userId = $db->query($qry)->fetchColumn(0);
        //echo "USERID: " . $userId ."</br>";

        $qry = "SELECT id FROM party WHERE userId = '$userId'";

        $partyId = $db->query($qry)->fetchColumn(0);
        //echo "PARTYID: " . $partyId . "</br>";

        $qry = "SELECT id FROM party WHERE userId = '$userId'";

        echo "Attacking Type <select id=\"attackType\">";
        foreach ($db->query('SELECT attackType from multipliers') as $row)
        {
        	echo "<option value=\"". $row['attackType'] ."\">" . $row['attackType'] . "</option>";
        }
        echo "</select></br><br/>";

        echo "<table border=\"1\">";
        echo "<tr><th>Name</th><th>Type 1</th><th>Type 2</th><th>Modifier</th></tr>";
        $i = 0;
        foreach ($db->query("SELECT * from pokemon where partyId = '$partyId'") as $row)
        {
            echo "<tr><td>" . $row['name']  . "</td>" .
            	 "<td class=\"stuff\">"     . $row['type1'] . "</td>" . 
            	 "<td class=\"stuff\">"     . $row['type2'] . "</td>" .
            	 "<td id=\"modifier$i\"></td></tr>";
            	 $i += 2;
        }
        echo "</table></br>";

        //echo "<p id=\"modifier\">Damage Modifier: </p>";

        ?>

        <button type="button" onclick="buildQuery()">Calculate</button>
    </body>
</html>