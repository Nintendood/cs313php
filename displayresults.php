<!DOCTYPE html>
<html>
	<head>
		<title>Amiibo Survey: Results</title>
		<link rel="stylesheet" type="text/css" href="survey.css">
	</head>
	<body>
	<div>
<h1><img src="amiibo_logo.png" class="logo"/></h1>
		<?php
		//0 - number of people who taken the survey (1)
		//1 - how many people answered "0"
		//2 - how many people answered "1 - 4"
		//3 - how many people answered "5 - 10"
		//4 - how many people answered "11 - 20"
		//5 - how many people answered "20 or more"
		//6 - how many people answered "Yes"
		//7 - how many people answered "No"
		//8 - how many people answered "Not Sure"
		//9 - reason 0
		//10 - reason 1
		//11 - reason 2
		//12 - reason 3
		//13 - Average satisfaction rating

		if (isset($_COOKIE["sessionNumber"]))
		{
			echo "<p>You have already submitted survey information.</p>".
				 "<p>Unlike some retailers, this survey has a 1-per-customer limit in order to keep the data as accurate as possible.</p>" .
				 "<p>Not to worry though! You can take a look at the current results whenever you like!</p>" .
				 "<br/>";
		}
		$fileData = [];
    	//UPDATE THE DATA
		$file = fopen("surveyData.txt", "r");
		if ($file)
		{
			$content = file_get_contents("surveyData.txt");
			$fileData = explode("\n", $content);
		}


			$displayData = [];
			for ($i = 0; $i < 13; $i++)
			{
				$val = ($fileData[$i] / $fileData[0]) * 100;
				$displayData[$i] = round($val, 2, PHP_ROUND_HALF_DOWN);
			}

			$displayData[13] = round($fileData[13] / $fileData[0], 2, PHP_ROUND_HALF_DOWN);

			echo "<h5>People who have taken the survey: $fileData[0]</h5>";
			echo "<table class=\"result\">";
			echo "  <th><h5>How many Amiibo do you currently own?<h5></th>";
			echo "  <tr>";
			echo "    <td><p>0</p></td>";
			echo "    <td><p>$displayData[1]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>1 - 4</p></td>";
			echo "    <td><p>$displayData[2]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>5 - 10</p></td>";
			echo "    <td><p>$displayData[3]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>11 - 20</p></td>";
			echo "    <td><p>$displayData[4]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>21 or more</p></td>";
			echo "    <td><p>$displayData[5]%</p></td>";
			echo "  </tr>";
			echo "</table>";
			echo "<table class=\"result\">";
			echo "  <th><h5>Do you plan on purchasing more Amiibo?</h5></th>";
			echo "  <tr>";
			echo "    <td><p>Yes</p></td>";
			echo "    <td><p>$displayData[6]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>No</p></td>";
			echo "    <td><p>$displayData[7]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>Not Sure</p></td>";
			echo "    <td><p>$displayData[8]%</p></td>";
			echo "  </tr>";
			echo "</table>";
			echo "<table class=\"result\">";
			echo "  <th><h5>Why do you purchase Amiibo?</h5></th>";
			echo "  <tr>";
			echo "    <td><p>I want to unlock additional content in certain Nintendo games</p></td>";
			echo "    <td><p>$displayData[9]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>I am a fan of the character or franchise represented (such as Mario or Link)</p></td>";
			echo "    <td><p>$displayData[10]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>I find them to be reasonabley priced</p></td>";
			echo "    <td><p>$displayData[11]%</p></td>";
			echo "  </tr>";
			echo "  <tr>";
			echo "    <td><p>I am addicted and have no choice but to collect every single one</p></td>";
			echo "    <td><p>$displayData[12]%</p></td>";
			echo "  </tr>";
			echo "</table>";
			echo "<h5>How satisifed are you with the in-game content available only with use of Amiibo figures?</h5>";
			echo "<p>Average Score: $displayData[13]%</p>";
		?>
		<br/>
	</div>
	</body>
</html>