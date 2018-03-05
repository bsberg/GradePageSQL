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
			echo "<TH> points </TH> ";
			echo "</TR>";

			foreach($db->query("Select TotalG.exName as exName, score, points from TotalG inner join Exam on TotalG.exName = Exam.exName where SID=\"".$_SESSION['login_user']."\" order by TotalG.exName") as $row) {
				echo "<TR>";
				echo '<form method="post" action="detailed_grades.php">';
				echo '<input type="hidden" name="exam" value="'.$row[0].'">';
				echo '<input type="hidden" name="score" value="'.$row[1].'">';
				echo '<input type="hidden" name="points" value="'.$row[2].'">';
				echo "<TD>".$row[0]."</TD>";
				echo "<TD>".$row[1]."/".$row[2]."</TD>";
				if(isset($row[1]) || !empty($row[1])) {
					echo '<TD> <input type="submit" name="take" value="See breakdown"> </TD>';
				}
				echo '</form>';
				echo "</TR>";
			}
			echo "</table>";
		?>

		<h3><a href = "welcome.php">Home</a> | <a href = "logout.php">Sign Out</a></h3>
	</body>
	
</html>

