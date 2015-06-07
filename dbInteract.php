<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $op = $_GET['op'];

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

       switch ($op) {

        case 'save':
            $id = $_GET['id'];
            $name = $_GET['name'];
            $defendType1 = $_GET['defendType1'];
            $defendType2 = $_GET['defendType2'];
            $qry = $db->prepare("UPDATE pokemon SET name=:name , type1=:defendType1 , type2=:defendType2
                    WHERE id=:id;");
            $qry->bindValue(':name', $name);
            $qry->bindValue(':defendType1', $defendType1);
            $qry->bindValue(':defendType2', $defendType2);
            $qry->bindValue(':id', $id);
            $qry->execute();
        break;

        case 'get':
            $attackType = $_GET['attackType'];
            $defendType1 = $_GET['defendType1'];
            $defendType2 = $_GET['defendType2'];
            $modifierid = $_GET['modifierid'];

            $qry = $db->prepare("SELECT * FROM multipliers WHERE attackType = :at;");
            $qry->bindParam(':at', $attackType);
            $qry->execute();

            while ($row = $qry->fetch(PDO::FETCH_ASSOC))
            {
                $modifier = $row[$defendType1] * 1.0;

                if ($defendType2 != "blank")
                {
                    $modifier *= $row[$defendType2];
                }
            }

            echo $modifierid . "," . $modifier . "x";
        break;

        case 'add':
            $partyId = $_GET['partyId'];
            $qry = $db->prepare("INSERT INTO pokemon (name, type1, type2, partyId) 
                    VALUES ('Pokemon Name', 'normal' , 'blank' , :partyId);");
            $qry->bindValue(':partyId', $partyId);
            $qry->execute();
        break;

        case 'delete':
            $id = $_GET['id'];
            $qry = $db->prepare("DELETE FROM pokemon WHERE id = :id;");
            $qry->bindValue(':id', $id);
            $qry->execute();
        break;

        case 'addParty':
            $name = $_GET['name'];
            $userId = $_GET['userId'];
            echo "We're here! " . $name . " " . $userId;
            $qry = $db->prepare("INSERT INTO party (userId, name) VALUES (:userId, :name);");
            $qry->bindValue(':userId', $userId);
            $qry->bindValue(':name', $name);
            $qry->execute();
        break;

        case 'deleteParty':
            $id = $_GET['id'];
            $qry = $db->prepare("DELETE FROM pokemon WHERE partyId = :id;");
            $qry->bindValue(':id', $id);
            $qry->execute();
            $qry = $db->prepare("DELETE FROM party WHERE id = :id;");
            $qry->bindValue(':id', $id);
            $qry->execute();
        break;
        
        default:
        break;
    }
?>