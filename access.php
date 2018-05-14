<?php
include('config.php');

session_start();
if (!isset($_SESSION['loggedIn'])) {
	$_SESSION['loggedIn'] = false;
}

if (isset($_POST['password'])) {
	if (sha1($_POST['password']) == $password) {
		$_SESSION['loggedIn'] = true;
	} 
	else {
		die ('Incorrect password.');
	}
} 

if (!$_SESSION['loggedIn']): ?>

<html>
	<head>
		<!--<link rel="icon" href="todo.png">-->
		<meta content="lgsm-cp" property="og:title" />
		<meta content="LinuxGSM control panel, currently work-in-progress" property="og:description" />
		<title>Login</title>
		<style>
		.body {
			font-family: monospace; 
			font-size: 180%; 
			background-color: #F0F0F0; 
			color: #000;
			text-align: center;
		}
		.buttons input {
			background-color: #F0F0F0;
			border: none;
			color: #000;
			padding: 8px;
			text-align: center;
			text-decoration: none;
			font-weight: bold;
			font-size: 16px;
			font-family: monospace;
			margin: 1px;
			cursor: pointer;
			width: 200px;
			height: 40px;
		}
		.login {
			padding-top: 20px;
			width: 400px;
			height: 400px;
			text-align: center;
			margin: 0 auto;
		}
		</style>
	</head>
	<body>
		<div class="login">
			<p style="font-family: monospace; font-size: 180%;">Enter credentials</p>
			<div class="buttons">
				<form method="post">
					<input type="password" name="password"><input type="submit" name="submit" value=">">
				</form>
			</div>
		</div>
	</body>
</html>

<?php
exit();
endif;
?>

