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
    <div>
        <h1><img src="pokemon.png" class="logo"/></h1>
        <h2>Type Modifier Calculator</h2>
        <br/>
        <?php


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
            echo 'Error!: ' . $ex->getMessage();
            die();
        }

        $username = $_SESSION['username'];

        $qry = "SELECT id FROM users WHERE username = '$username'";

        $userId = $db->query($qry)->fetchColumn(0);

        echo "<p>User: " . $username;


/////////////////////////////////////////////////////////////////////////////////////////

        $partyNameSent = isset($_POST['partyName']);
        $partyName = "";
        $partyId = 0;

        if ($partyNameSent)
        {
            $partyName = $_POST['partyName'];
            $qry = $db->prepare("SELECT id FROM party WHERE name = :partyName LIMIT 1;");
            $qry->bindValue(':partyName', $partyName);
            $qry->execute();
            while ($row = $qry->fetch(PDO::FETCH_ASSOC))
            {
                $partyId = $row['id'];
            }
        }
        else
        {
            $qry = $db->prepare("SELECT * FROM party WHERE userId = :userId LIMIT 1;");
            $qry->bindValue(':userId', $userId);
            $qry->execute();
            while ($row = $qry->fetch(PDO::FETCH_ASSOC))
            {
                $partyName = $row['name'];
                $partyId = $row['id'];
            }
        }

        echo "<form action=\"typeCalculator2.php\" method=\"post\">";
        echo "<p>Party <select name=\"partyName\" id=\"partyName\" onchange=\"this.form.submit()\"></p>";

        $qry = "SELECT * FROM party WHERE userId = '$userId'";
        foreach ($db->query($qry) as $row)
        {
            if ($row['name'] == $partyName)
                echo "<option selected=\"selected\" value=\"". $row['name'] ."\">" . $row['name'] . "</option>";
            else
                 echo "<option value=\"". $row['name'] ."\">" . $row['name'] . "</option>";
    
        }
        echo "</select>";
        echo "</form><br/>";

        echo "<p id=\"partyId\" hidden>$partyId</p>";


///////////////////////////////////////////////////////////////////////////////////////


        echo "<p>Attacking Type <select id=\"attackType\" onchange=\"buildQuery()\"></p>";
        echo "<option value=\"blank\"></option>";
        foreach ($db->query('SELECT attackType from multipliers') as $row)
        {
        	echo "<option value=\"". $row['attackType'] ."\">" . $row['attackType'] . "</option>";
        }
        echo "</select><br/><br/>";

        echo "<table border=\"1\">";
        echo "<tr><th>Name</th><th>Type 1</th><th>Type 2</th><th>Modifier</th><th>Delete</th></tr>";

        $i = 0;
        foreach ($db->query("SELECT * from pokemon where partyId = '$partyId'") as $row)
        {
            echo "<tr><td>";
            nameBox($row['name'], $i);
            echo "</td><td><select id=\"defendType1$i\" onchange=\"buildQuery()\" class=\"stuff\">";
            createPokemonSelector($row['type1'], $db, 1);
            echo "<td><select id=\"defendType2$i\" onchange=\"buildQuery()\" class=\"stuff\">";
            createPokemonSelector($row['type2'], $db, 2);
            echo "<td><p id=\"modifier$i\"></p></td>";
            echo "<td><p><button type=\"add\" onclick=\"buildDeleteData(" . $row['id'] . ")\"> - </button></p></td></tr>";
            echo "<p id=\"pokemonID$i\" hidden>" . $row['id'] . "</p>";
            $i += 2;
        }
        if ($i < 12)
            echo "<tr><td><p><button type=\"delete\" onclick=\"buildAddData()\">Add Pok&eacute;mon</button></p></td><td><p>-</p></td><td><p>-</p></td><td><p>-</p></td><td><p>-</p></td></tr>";
        echo "</table></br>";

        function createPokemonSelector($defaultValue, $db, $typeNum)
        {
            if ($typeNum == 2)
            {
                echo "<option value=\"blank\"></option>";
            }

            foreach ($db->query('SELECT attackType from multipliers') as $row)
            {
                if ($row['attackType'] == $defaultValue)
                {
                    echo "<option selected=\"selected\" value=\"". $row['attackType'] ."\">" . $row['attackType'] . "</option>";
                }
                else
                {
                    echo "<option value=\"". $row['attackType'] ."\">" . $row['attackType'] . "</option>";   
                }
            }        
            echo "</select></td>";
        }

        function nameBox($name, $i)
        {
            echo "<p><input type=\"input\" id=\"name$i\" value=\"$name\"/></p>";
        }

        echo "<button type=\"button\" onclick=\"addPartyData(" . $userId . ")\">New Party</button> ";
        echo "<button type=\"button\" onclick=\"buildSaveData()\">Save Party</button> ";
        echo "<button type=\"button\" onclick=\"deletePartyData(" . $partyId . ")\">Delete Party</button>";
        ?>
        
        </br>
        </div>
    </body>
</html>