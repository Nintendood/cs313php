<?php
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    $op = $_GET['op'];

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

    switch ($op) {

        case 'save':
            $id = $_GET['id'];
            $name = $_GET['name'];
            $defendType1 = $_GET['defendType1'];
            $defendType2 = $_GET['defendType2'];
            $qry = "UPDATE pokemon SET name='$name' , type1='$defendType1' , type2='$defendType2'
                    WHERE id=$id;";
            $db->query($qry);
        break;

        case 'get':
            $attackType = $_GET['attackType'];
            $defendType1 = $_GET['defendType1'];
            $defendType2 = $_GET['defendType2'];
            $modifierid = $_GET['modifierid'];

            $qry1 = "SELECT " . $defendType1 . " FROM multipliers WHERE attackType = '" . $attackType . "';";
            $qry2 = "SELECT " . $defendType2 . " FROM multipliers WHERE attackType = '" . $attackType . "';";

            foreach ($db->query($qry1) as $row)
                {
                    $modifier = $row[$defendType1] * 1.0;
                }
                if ($defendType2 != "blank")
                {
                    foreach ($db->query($qry2) as $row)
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