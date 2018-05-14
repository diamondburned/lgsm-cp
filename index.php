<?php
	require('access.php');
?>

<html>
	<head>
		<title>LinuxGSM WebCP WIP</title>
		<link rel="stylesheet" type="text/css" href="style.css" />
	</head>
	<body>
		<header>
			<h1 style="font-size:300%;">
				<a href="https://github.com/diamondburned/lgsm-cp">
					<img src="https://github.com/GameServerManagers/LinuxGSM/wiki/images/brand/colour_white/LinuxGSM_colour_white_logo_512.png" > LinuxGSM_ Control Panel
				</a>
			</h1>
		</header>
		<div class="topbar">
			<form method="post" style="display:inline;">
				<div class="buttons">
					<div class="dropdown">
						<button class="dropbtn">Actions ▼</button>
						<div class="dropdown-content">
							<input type="submit" name="details" value="Details" />
							<input type="submit" name="monitor" value="Monitor" />
							<input type="submit" name="restart" value="Restart/Start" />
							<input type="submit" name="stop" value="Stop" />
							<!--<input type="submit" name="update" value="Update" />-->
						</div>
					</div>
					<div class="dropdown">
						<button class="dropbtn">Logs ▼</button>
						<div class="dropdown-content">
							<input type="submit" name="consolelog" value="Latest Console log" />
							<input type="submit" name="alertlog" value="Latest Alert log" />
						</div>
					</div>
				</div>
			</form>
			<form action="logout.php" method="get" style="display:inline;">
				<input type="submit" value="Log Out" class="logout" />
			</form>
		</div>
		<div class="status">
			<form method="post" style="display:inline;">
				<div class="output">
					<div class="input">
						<input type="text" name="rconcmd" placeholder="Type RCON command here" style="width: 1100px;" />
						<input type="submit" name="rconrun" value="Run" style="width: 90px;" />
					</div>
			</form>
			<?php
				include('config.php');	
				ini_set('max_execution_time','1440M');
				$rconcmd = $_POST["rconcmd"];
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['details'])) {
					$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game details 2>&1";
					while (@ ob_end_flush());
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
					while (@ ob_end_flush());
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
					while (@ ob_end_flush());
					$proc = popen($cmd, 'r');
					echo '<pre>';
					while (!feof($proc)) {
						echo fread($proc, 4096);
						@ flush();
					}
					echo '</pre>';
				}
				/*if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update'])) {
					$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game update 2>&1";
					while (@ ob_end_flush());
					$proc = popen($cmd, 'r');
					echo '<pre>';
					while (!feof($proc)) {
						echo fread($proc, 4096);
						@ flush();
					}
					echo '</pre>';
				}*/ // commented out because update doesn't do live output right now
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['stop'])) {
					$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game stop 2>&1";
					while (@ ob_end_flush());
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
					while (@ ob_end_flush());
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
					while (@ ob_end_flush());
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
					while (@ ob_end_flush());
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
	</body>
</html>

