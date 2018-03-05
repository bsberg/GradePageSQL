<html>
	
	<head>
		
	</head>
	
	<body>

	 	<?php
			include('session.php');
			foreach($_POST as $key => $value) {
				if (is_int($key)) {
					$grades = $db->prepare('Insert into Grades values(:exName, :SID, :QID, :points) on duplicate key update exName = :exName, SID = :SID, QID = :QID, pointsE = :points');
					$response = $db->prepare('Insert into Response values(:SID, :exName, :QID, :response) on duplicate key update exName = :exName, SID = :SID, QID = :QID, response = :response');
					$response->execute(array( ':exName' => $_POST['exName'],
												':SID' => $_SESSION['login_user'],
												':QID' => $key,
												':response' => $value
										));
					$correct = $db->prepare('select isAnswer from Choices where QID = :QID and choice = :choice');
					$correct->execute(array(':QID' => $key,':choice' => $value));
					$correct = $correct->fetch();
					if (filter_var($correct["isAnswer"], FILTER_VALIDATE_BOOLEAN)) {
						$grades->execute(array( ':exName' => $_POST['exName'],
												':SID' => $_SESSION['login_user'],
												':QID' => $key,
												':points' => $_POST[$key.'Points']
										));	
					} else { //otherwise get 0 points
						$grades->execute(array(':exName' => $_POST['exName'],
																		':SID' => $_SESSION['login_user'],
																		':QID' => $key,
																		':points' => 0
																));	
					}	
				}	
			}
			
		?>
	<p>Thank you <?php echo $_SESSION['login_user']?>. Your exam has been submitted for grading. Please check the <a href="grades.php">grades</a> page.</p>
	</body>
	
</html>

