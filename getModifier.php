<?php
	$attackType = $_GET['attackType'];
	$defendType1 = $_GET['defendType1'];
	$defendType2 = $_GET['defendType2'];
	$modifierid = $_GET['modifierid'];

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
?>