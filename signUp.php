<?php
	function RegisterUser( $name, $eMail, $pass){
     $DBservername = 'localhost';
     $DBusername = 'root';
     $DBpassword = '';
     $DBname = 'cardgame';
     /*
     $DBusername = 'id2248842_cardgame';
     $DBpassword = '1qaz2wsx3edc';
     $DBname = 'id2248842_cardgame';*/
		$conn = new mysqli($DBservername, $DBusername, $DBpassword, $DBname);
		if( $conn->connect_error){
		  echo("Connection failed: " . $conn->connect_error);
		  return;
		}
		$sql = "SELECT Id FROM users WHERE userName='".$name."';";
		$result = $conn->query($sql);
		if( $result->num_rows > 0){
			echo "<h4 style='color:red;'>Already exist UserName!</h4>";
			return;
		}
		if( $name == "admin")
			$sql = "INSERT INTO users(userName, password, role) VALUES('". $name ."','" . $pass . "', '0')";
		else
			$sql = "INSERT INTO users(userName, password) VALUES('". $name ."','" . $pass . "')";
		if( $conn->query($sql) === TRUE){
			header("Location: index.php");
		}
		else{
		}
		$conn->close();
	}

	if(isset($_POST['userName']) && !isset($userName)){
		$userName = $_POST['userName'];
		$email = $_POST['email'];
		$password = $_POST['password'];
		RegisterUser($userName, $email, $password);
	}

?>
<link rel="stylesheet" type="text/css" href="assets/card_game.css">
<body style="background-color: aliceblue;">
	<h1>Sign Up</h1>
	<form action="" method="post" style="top: 100px; position: relative;">
		<table>
			<tr>
				<td>UserName:</td>
				<td><input type="text" name="userName" placeholder="Enter UserName" value="" required></td>
			</tr>
			<tr style="display: none;">
				<td>Email:</td>
				<td><input type="email" name="email" placeholder="Enter Email" value=""></td>
			</tr>
			<tr>
				<td>Password:</td>
				<td><input type="password" name="password" placeholder="Enter Password" value="" required></td>
			</tr>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" value="SignUp"><a class="centerClass" href="index.php" style="padding-left: 20px;">goto login</a></td>
			</tr>
		</table>
	</form>
</body>
