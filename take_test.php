<html>
	
	<head>
		
	</head>
	
	<body>
	 	<?php
			include('session.php');

			$name = $_POST["exName"];

			//$exam = $db->prepare("SELECT name, points, created FROM tests where name=:name");
			//$exam = $exam->execute(array(":name" => $name));
			echo "<h1>".$_POST["exName"]."</h1>";
			echo "<h3>".$_POST["points"]." points total</h3>";
			echo "<h3>Due: ".$_POST["due"]."</h3>";

			echo '<form method="post" action="submit.php">';
			echo '<input type="hidden" name="exName" value="'.$_POST["exName"].'">';
			echo "<ol>";
			foreach($db->query('select QID, points, textQ from TestQ natural join Question where exName="'.$name.'"') as $row) {

				echo "<li>"."<p>"."(".$row[1]." points) ".$row[2]."</p>";
				echo '<input type="hidden" name="'.$row[0].'Points" value="'.$row[1].'">';
				foreach ($db->query("select * from Choices where QID=".$row[0]) as $choice) {
					echo '<input type="radio" name="'.$row[0].'" value="'.$choice[1].'"> '.$choice[1].') '.$choice[2].'<br/>';
				}
				echo "</li>";
			}

			echo "</ol>";
			echo '<TD> <input type="submit" name="submit" value="Submit"> </TD>';
			echo '</form>';
		?>

		<h3><a href = "welcome.php">Home</a> | <a href = "logout.php">Sign Out</a></h3>
	</body>
	
</html>

