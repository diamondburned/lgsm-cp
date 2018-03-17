<html>
<head>
	<title>LinuxGSM WebCP WIP</title>
	<style>
	body {
		background-color: #1D1D1D;
		color: #FFF;
		font-family: monospace;
	}
	.status {
		max-width: 1200px;
		background-color: #272727;
		min-height: 100vh;
		max-height: 100000%;
		position: relative;
		margin: auto;
		width: 90%;
	}
	.status input {
		background-color: #F0F0F0;
		border: none;
		color: #000;
		padding: 8px;
		text-align: center;
		text-decoration: none;
		font-weight: bold;
		font-size: 16px;
		margin: 1px;
		cursor: pointer;
		width: 200px;
	}
	input.remember {
		padding: 0px;
		margin-top: 6px;
		cursor: pointer;
		width: 20px;
		line-height: 2px;
	}
	p.remember{
		display: inline-block;
		margin-left: 3px;
	}
	.status form {
	}
	.output {
		min-height: 700px;
		border: none;
		max-width: 100%;
		background-color: rgba(0,0,0,0.5);
	}
	.output pre {
		margin: 20px;
		white-space: pre-wrap;
	}
	.output input {
		background-color: rgba(0,0,0,0);
		border: none;
		border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		color: #FFF;
		padding: 4px;
		text-align: center;
		font-family: monospace !important;
		text-decoration: none;
		font-size: 16px;
		margin: 0px;
		cursor: pointer;
		width: 200px;
	}
	.output form {
		display: flex;
		justify-content: space-between;
	}
	.buttons {
		display: inline-block;
		margin-left: 20px;
		margin-right: 20px;
	}
	.dropbtn {
	    background-color: #F0F0F0;
		border: none;
		color: #000;
		padding: 8px;
		text-align: center;
		text-decoration: none;
		font-weight: bold;
		font-size: 16px;
		margin: 1px;
		cursor: pointer;
		width: 200px;
	}
	.dropdown {
	    position: relative;
	    display: inline-block;
	}
	.dropdown-content {
	    display: none;
	    position: absolute;
	    background-color: #202020;
	    min-width: 160px;
	    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	    z-index: 1;
	}
	.dropdown-content input {
	    color: white;
	    padding: 12px 16px;
	    text-decoration: none;
	    display: block;
	    background-color: rgba(0,0,0,0.2);
	}
	.dropdown-content a:hover {background-color: #202020}
	.dropdown:hover .dropdown-content {
	    display: block;
	    background-clor: #101010;
	}
	.dropdown:hover .dropbtn {
	    background-color: #CCCCCC;
	}
	</style>
</head>
<body>
	<div class="status">
		<div class="buttons">
			<form method="post">
				<h3>RCON tools</h3>
				<input type="text" name="hostname" placeholder="Hostname" value="<?php
					echo $_COOKIE['hostname']; ?>" />
				<input type="text" name="port" placeholder="Port" value="<?php
					echo $_COOKIE['port']; ?>" />
				<input type="password" name="password" placeholder="Password" value="" />
				<input type="submit" name="status" value="Print Status" />
				<h3>LinuxGSM tools</h3>
				<input type="text" name="lgsmuser" placeholder="LGSM User" value="<?php
					echo $_COOKIE['lgsmuser']; ?>" />
				<input type="text" name="game" placeholder="Game" value="<?php
					echo $_COOKIE['game']; ?>" />
				<input type="submit" name="details" value="Print Details" />
				<div class="dropdown">
					<button class="dropbtn">Logs ▼</button>
					<div class="dropdown-content">
						<input type="submit" name="consolelog" value="Latest Console log" />
						<input type="submit" name="errorlog" value="Latest Error log" />
						<input type="submit" name="alertlog" value="Latest Alert log" />
					</div>
				</div>
				<br />
				<input class="remember" type="checkbox" name="remember" value="1"><p class="remember">Remember information. Stored items are hostname, port, LGSM user and game.</p>
			</form>
		</div>
		<div class="output">
		<form action="index.php" method="post">
			<input type="text" name="rconcmd" value="Type RCON command here" style="width: 80%;" />
			<input type="submit" name="rconrun" value="Run command" style="width: 200px;" />
		</form>
		<?php
			$host = $_POST["hostname"];
			$port = $_POST["port"];
			$rconpw = $_POST["password"];
			$lgsmuser = $_POST["lgsmuser"];
			$game = $_POST["game"];
			$cookietime = time() + 86400;
			if($_POST['remember']) {
				setcookie('hostname', $_POST['hostname'], $cookietime);
				setcookie('port', $_POST['port'], $cookietime);
				setcookie('lgsmuser', $_POST['lgsmuser'], $cookietime);
				setcookie('game', $_POST['game'], $cookietime);
				setcookie('remember', $_POST['remember'], $cookietime);
			}
			elseif(!$_POST['remember']) {
				if(isset($_COOKIE['hostname'])) {
					$past = time() - 100;
					setcookie(hostname, gone, $past);
				}
				if(isset($_COOKIE['port'])) {
					$past = time() - 100;
					setcookie(port, gone, $past);
				}
				if(isset($_COOKIE['lgsmuser'])) {
					$past = time() - 100;
					setcookie(lgsmuser, gone, $past);
				}
				if(isset($_COOKIE['game'])) {
					$past = time() - 100;
					setcookie(game, gone, $past);
				}
				if(isset($_COOKIE['remember'])) {
					$past = time() - 100;
					setcookie(remember, gone, $past);
				}
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['status'])) {
				$status_stdout = shell_exec("./rcon -H $host -p $port -P $rconpw status");
				echo "<pre>$status_stdout</pre>";
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['rconrun'])) {
				$status_stdout = shell_exec("./rcon -H $host -p $port -P $rconpw ' . $rconcmd . '");
				echo "<pre>$status_stdout</pre>";
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['details'])) {
				$details_stdout = shell_exec("/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/tf2server details");
				echo "<pre>$details_stdout<pre>";
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['errorlog'])) {
				$errorlog_stdout = shell_exec("cat /home/$lgsmuser/serverfiles/tf/addons/sourcemod/logs/errors_$(date +%Y%m%d).log");
				echo "<pre>$errorlog_stdout</pre>";
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['consolelog'])) {
				$consolelog_stdout = shell_exec("cat /home/$lgsmuser/log/console/tf2server-console.log");
				echo "<pre>$consolelog_stdout</pre>";
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['alertlog'])) {
				$alertlog_stdout = shell_exec("cat /home/$lgsmuser/log/script/tf2server-alert.log");
				echo "<pre>$alertlog_stdout</pre>";
			}
		?>
		</div>
	</div>
</body>
</html>

