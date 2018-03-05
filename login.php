<?php
	try {
		include('config.php');
		session_start();
		$error = "";
		if($_SERVER["REQUEST_METHOD"] == "POST") {
			// username and password sent from form 
			$myusername = $_POST['username'];
			$mypassword = $_POST['password'];
			//$mypassword = hash("sha256",$_POST['password']);
			$statement = $db->prepare("SELECT password, salt FROM Login WHERE SID = :myusername");
			$statement->execute(array(':myusername' => $myusername));	
			$result = $statement->fetch();
			$salted = $result["salt"].$mypassword;
			$hash = hash("sha256", $result["salt"].$mypassword);
			if(hash_equals($hash, $result["password"])) {
				$_SESSION['login_user'] = $myusername;
				
				header("location: welcome.php");
			}else {
				$error = "Your Login Name or Password is invalid";
			}
		}
	} catch (PDOException $e) {
		print "Error!".$e -> getMessage()."<br />";
		die();
	}
?>
<html>
	
	<head>
		<title>Login Page</title>
		
		<style type = "text/css">
			body {
				font-family:Arial, Helvetica, sans-serif;
				font-size:14px;
			}
			label {
				font-weight:bold;
				width:100px;
				font-size:14px;
			}
			.box {
				border:#666666 solid 1px;
			}
		</style>
		
	</head>
	
	<body bgcolor = "#FFFFFF">
	
		<div align = "center">
			<div style = "width:300px; border: solid 1px #333333; " align = "left">
				<div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
				<div style = "margin:30px">
					
					<form action = "" method = "post">
						<label>Username  :</label><input type = "text" name = "username" class = "box"/><br /><br />
						<label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
						<input type = "submit" value = " Submit "/><br />
					</form>
					
					<div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error?></div>
					
				</div>
				
			</div>
			
		</div>

	</body>
</html>
