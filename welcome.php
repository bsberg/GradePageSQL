<?php
	include('session.php');
?>
<html>
	
	<head>
		<title>Welcome </title>
	</head>
	
	<body>
		<h1>Welcome <?php echo $login_session; ?> to my fake exam site!</h1> 
		<p>Would you like to take a <a href="/~njvelat/final/tests.php">test?</a></p>
		<p>Or would you like to see your <a href="/~njvelat/final/grades.php">grades?</a></p>
		<h2><a href = "logout.php">Sign Out</a></h2>
	</body>
	
</html>
