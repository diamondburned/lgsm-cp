<html>
<head>
	<title>LinuxGSM WebCP WIP</title>
	<style>
	body {
		background-color: #1D1D1D;
		color: #FFF;
		font-family: monospace;
		padding: 0;
		margin: 0;
		background-repeat: repeat;
		background-attachment: fixed;
		min-width: 100%;
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
	header { 
		text-align: center; 
		border-bottom: 4px solid #0A0A0A; 
		padding: 8px;
		background-color: #0F0F0F;
	}
	header h1 a {
		color: #FFF;
		text-decoration: none;
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
	.input {
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
	<header>
		<h1 style="font-size:300%;"><a href="https://github.com/diamondburned/lgsm-cp"><img src="https://github.com/GameServerManagers/LinuxGSM/wiki/images/brand/colour_white/LinuxGSM_colour_white_logo.svg" onerror="this.src='https://github.com/GameServerManagers/LinuxGSM/wiki/images/brand/colour_white/LinuxGSM_colour_white_logo_512.png'" style="height: 62px;position: absolute;margin-left: -52px;margin-top: -6px;"> LinuxGSM_ Control Panel</a></h1>
	</header>
	<div class="status">
		<form method="post">
		<div class="buttons">
			<h3>LinuxGSM tools</h3>
			<input type="text" name="lgsmuser_post" placeholder="LGSM User" value="<?php echo $_COOKIE['lgsmuser']; ?>" />
			<input type="text" name="game" placeholder="Game" value="<?php echo $_COOKIE['game']; ?>" />
			<div class="dropdown">
				<button class="dropbtn">Actions ▼</button>
				<div class="dropdown-content">
					<input type="submit" name="details" value="Details" />
					<input type="submit" name="monitor" value="Monitor" />
					<input type="submit" name="restart" value="Restart/Start" />
					<input type="submit" name="stop" value="Stop" />
					<input type="submit" name="update" value="Update" />
				</div>
			</div>
			<div class="dropdown">
				<button class="dropbtn">Logs ▼</button>
				<div class="dropdown-content">
					<input type="submit" name="consolelog" value="Latest Console log" />
					<input type="submit" name="errorlog" value="Latest Error log" />
					<input type="submit" name="alertlog" value="Latest Alert log" />
				</div>
			</div>
			<br />
			<input class="remember" type="checkbox" name="remember" value="1"><p class="remember">Remember information. Stored items are LGSM username, game and the remember checkbox. This may not work.</p>
		</div>
		<div class="output">
			<div class="input">
				<input type="text" name="rconcmd" placeholder="Type RCON command here" style="width: 1100px;" />
				<input type="submit" name="rconrun" value="Run" style="width: 90px;" />
			</div>
		</form>
		<?php
			ini_set('max_execution_time','1440M');
			$lgsmuser = $_POST["lgsmuser_post"];
			$game = $_POST["game"];
			$rconcmd = $_POST["rconcmd"];
			$cookietime = time() + 86400;
			if($_POST['remember']) {
				setcookie('lgsmuser', $lgsmuser, $cookietime);
				setcookie('game', $game, $cookietime);
			}
			elseif(!$_POST['remember']) {
				if(isset($_COOKIE['lgsmuser'])) {
					$past = time() - 100;
					setcookie(lgsmuser, gone, $past);
				}
				if(isset($_COOKIE['game'])) {
					$past = time() - 100;
					setcookie(game, gone, $past);
				}
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['details'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game details 2>&1";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['monitor'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game monitor 2>&1";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['restart'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game restart 2>&1";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game update 2>&1";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['stop'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game stop 2>&1";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['errorlog'])) {
				$cmd = "cat /home/$lgsmuser/serverfiles/tf/addons/sourcemod/logs/errors_$(date +%Y%m%d).log 2>&1 | tac";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['consolelog'])) {
				$cmd = "cat /home/$lgsmuser/log/console/'$game'-console.log 2>&1 | tac";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['alertlog'])) {
				$cmd = "cat /home/$lgsmuser/log/script/'$game'-alert.log 2>&1 | tac";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
			if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['rconrun'])) {
				$cmd = "/usr/bin/sudo -u $lgsmuser ./tmux-run '$rconcmd' 2>&1 | tac";
				while (@ ob_end_flush()); // end all output buffers if any
				$proc = popen($cmd, 'r');
				echo '<pre>';
				while (!feof($proc)) {
					echo fread($proc, 4096);
					@ flush();
				}
				echo '</pre>';
			}
		?>
		</div>
	</div>
</body>
</html>

