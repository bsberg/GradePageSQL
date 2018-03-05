<html>
	
	<head>
		
	</head>
	
	<body>
	 	<?php
			include('session.php');
			$name = $_POST["exam"];
			$score = $_POST["score"];
			$max = $_POST["points"];
			$exam = $db->prepare('SELECT Grades.exName, SID, Grades.QID, pointsE, points, textQ
FROM Grades inner join TestQ on (Grades.exName = TestQ.exName and Grades.QID = TestQ.QID) inner join Question on Grades.QID = Question.QID where Grades.exName = :exName and SID = :SID;');
			$exam->execute(array(":exName"=> $name, ":SID" => $_SESSION["login_user"]));
			echo "<h1>".$name."</h1>";
			echo "<h3>Overall score: ".$score."</h3>";

			echo "<ol>";
			foreach($exam as $row) {
				echo "<li>"."<p>"."(".$row[3]."/".$row[4].") ".$row[5]."</p>";
				$response = $db->prepare("Select response from Response where exName = :exName and SID = :SID and QID = :QID");
				$response->execute(array(":exName"=> $name, ":SID" => $_SESSION["login_user"], ':QID' => $row[2]));
				$response = $response->fetch();
				echo "Your response: ".$response["response"]."<br/>";

				$correct = $db->prepare('select choice from Choices where QID = :QID and isAnswer=1');
				$correct->execute(array(':QID' => $row[2]));
				echo "Correct answer: " ;
				foreach($correct as $row) {
					echo $row[0]. ". ";
				}
				echo "<br>";
				echo "</li>";
			}

			echo "</ol>";
		?>

		<h3><a href = "welcome.php">Home</a> | <a href = "logout.php">Sign Out</a></h3>
	</body>

	
	
</html>

