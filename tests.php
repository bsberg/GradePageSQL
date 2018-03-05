<html>
	
	<head>
		
	</head>
	
	<body>
		<h1>List of tests</h1> 
	 	<?php
			include('session.php');
			echo "<table border='1'>";
			echo "<TR>";
			echo "<TH> name </TH> ";
			echo "<TH> created on </TH> ";
			echo "<TH> points </TH> ";
			echo "<TH> status </TH>";
			echo "</TR>";

			foreach($db->query("SELECT exName, created, points, due FROM Exam order by created") as $row) {
				echo "<TR>";
				echo '<form method="post" action="take_test.php">';
				echo '<input type="hidden" name="exName" value="'.$row[0].'">';
				echo '<input type="hidden" name="due" value="'.$row[3].'">';
				echo '<input type="hidden" name="points" value="'.$row[2].'">';
				echo "<TD>".$row[0]."</TD>";
				echo "<TD>".$row[1]."</TD>";
				echo "<TD>".$row[2]."</TD>";
				//if($row[3] == "Available") {
					//echo '<TD><span style="color: #00FF00">'.$row[3].'</span></TD>';
					echo '<TD> <input type="submit" name="take" value="Take"> </TD>';
				//} else {
				//	echo '<TD><span style="color: #FF9900">'.$row[3].'</span></TD>';
				//}
				echo '</form>';
				echo "</TR>";	
			}

			echo "</table>";

		?>

		<h3><a href = "welcome.php">Home</a> | <a href = "logout.php">Sign Out</a></h3>
	</body>
	
</html>

