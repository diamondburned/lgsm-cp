<?php
    session_start();
    $_SESSION['loggedIn'] = false;
?>
<html>
	<head>
		<title>Logged out.</title>
	</head>
	<body style="background-color: #1d1d1d; color: #FFF;">
		<div style="width: 100%; text-align: center;">
			<h1 style="font-family: monospace; font-size: 500%;">Logged out</h1>
			<a href="javascript:javascript:history.go(-1)">Click here to go back to previous page</a>
		</div>
	</body>
</html>
