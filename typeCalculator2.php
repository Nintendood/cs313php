<?php
session_start();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Pok&eacute;mon Type Modifier</title>
        <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script type="text/javascript" src="typeCalculator.js"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="calculator.css">
    </head>
    <!--need php_readonly somewhere -->
    <body>
    <div class="main">
        <h1><img src="pokemon.png" class="logo"/></h1>
        <h3>Type Modifier Calculator</h3>
        <br/>
        <?php
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
            echo 'Error!: ' . $ex->getMessage();
            die();
        }

        $username = $_SESSION['username'];

        $qry = "SELECT id FROM users WHERE username = '$username'";
        $qry = $db->prepare("SELECT id FROM users WHERE username = :username");
        $qry->bindValue(':username', $username);
        $qry->execute();
        while ($row = $qry->fetch(PDO::FETCH_ASSOC))
        {
            $userId = $row['id'];
        }


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
        echo "<p>" . $username . "'s Party <select name=\"partyName\" id=\"partyName\" onchange=\"this.form.submit()\"></p>";

        $qry = $db->prepare("SELECT * FROM party WHERE userId = :userId;");
        $qry->bindValue(':userId', $userId);
        $qry->execute();
        while ($row = $qry->fetch(PDO::FETCH_ASSOC))
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
        $qry = $db->prepare("SELECT attackType from multipliers");
        $qry->execute();
        while ($row = $qry->fetch(PDO::FETCH_ASSOC))
        {
            echo "<option id=\"" . $row['attackType'] . "\" value=\"". $row['attackType'] ."\">" . $row['attackType'] . "</option>";
        }
        echo "</select><br/><br/>";

        echo "<table>";
        echo "<tr><td>Delete</td><td>Name</td><td>Type 1</td><td>Type 2</td><td>Modifier</td></tr>";

        $i = 0;
        $qry = $db->prepare("SELECT * from pokemon where partyId = :partyId");
        $qry->bindValue(':partyId', $partyId);
        $qry->execute();
        while ($row = $qry->fetch(PDO::FETCH_ASSOC))
        {
            echo "<tr>";
            echo "<td><p><button class=\"btn btn-danger\" onclick=\"buildDeleteData(" . $row['id'] . ")\"> - </button></p></td>";
            echo "<td>";
            nameBox($row['name'], $i);
            echo "</td><td><select id=\"defendType1$i\" onchange=\"buildQuery();buildSaveData()\" class=\"stuff\">";
            createPokemonSelector($row['type1'], $db, 1);
            echo "<td><select id=\"defendType2$i\" onchange=\"buildQuery();buildSaveData()\" class=\"stuff\">";
            createPokemonSelector($row['type2'], $db, 2);
            echo "<td><p id=\"modifier$i\"></p></td>";
            echo "</tr>";
            echo "<p id=\"pokemonID$i\" hidden>" . $row['id'] . "</p>";
            $i += 2;
        }
        if ($i < 12)
            echo "<tr><td><p>-</p><td><p><button class=\"btn btn-primary\" onclick=\"buildAddData()\">Add Pok&eacute;mon</button>" . 
                 "</p></td></td><td><p>-</p></td><td><p>-</p></td><td><p>-</p></td></tr>";
        echo "</table></br>";

        function createPokemonSelector($defaultValue, $db, $typeNum)
        {
            if ($typeNum == 2)
            {
                echo "<option value=\"blank\"></option>";
            }

            $qry = $db->prepare("SELECT attackType from multipliers");
            $qry->execute();
            while ($row = $qry->fetch(PDO::FETCH_ASSOC))
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
            echo "<p><input class=\"form-control input-sm\" type=\"input\" id=\"name$i\" value=\"$name\" onchange=\"buildSaveData()\"/></p>";
        }

        echo "<button type=\"button\" class=\"btn btn-primary\" onclick=\"addPartyData(" . $userId . ")\">Add Party</button> ";
        echo "<button type=\"button\" class=\"btn btn-danger\" onclick=\"deletePartyData(" . $partyId . ")\">Delete Party</button>";
        ?>
        
        </br>
        </div>
    </body>
</html>