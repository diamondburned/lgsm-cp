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
			<div class="buttons">
				<div class="left-buttons">
					<form method="post">
						<div class="dropdown">
							<button class="dropbtn">Actions ▼</button>
							<div class="dropdown-content">
								<input type="submit" name="restart" value="Restart/Start" />
								<input type="submit" name="stop" value="Stop" />
								<input type="submit" name="monitor" value="Monitor" />
								<input type="submit" name="update" value="Update" />
							</div>
						</div>
						<div class="dropdown">
							<button class="dropbtn">Details ▼</button>
							<div class="dropdown-content">
								<input type="submit" name="sysinfo" value="System Statistics" />
								<input type="submit" name="details" value="Gameserver Details" />
								<input type="submit" name="update-progress" value="Update Progress" />
								<input type="submit" name="rconprint" value="Console Output" />
								<input type="submit" name="consolelog" value="Latest Console log" />
								<input type="submit" name="alertlog" value="Latest Alert log" />
							</div>
						</div>
					</form>
				</div>
				<div class="right-buttons">
					<form action="logout.php" method="get">
						<input type="submit" value="Log Out" class="logout" />
					</form>
				</div>
			</div>
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
				// https://www.binarytides.com/php-output-content-browser-realtime-buffering/
				ini_set('output_buffering', 'off');
				ini_set('zlib.output_compression', false);
				ini_set('max_execution_time','1440M');
				$rconcmd = $_POST["rconcmd"];
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['details'])) {
					$cmd = shell_exec('./script.sh details');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['monitor'])) {
					$cmd = shell_exec('./script.sh monitor');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['restart'])) {
					$cmd = shell_exec('./script.sh restart');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update'])) {
					$cmd = shell_exec('./script.sh update');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update-progress'])) {
					$cmd = shell_exec('./script.sh update-progress');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['stop'])) {
					$cmd = shell_exec('./script.sh stop');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['consolelog'])) {
					$cmd = shell_exec('./script.sh console');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['alertlog'])) {
					$cmd = shell_exec('./script.sh alert');
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['rconrun'])) {
					$cmd = shell_exec("/usr/bin/sudo -u $lgsmuser ./tmux-run run '$rconcmd' 2>&1 | tac");
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['rconprint'])) {
					$cmd = shell_exec("/usr/bin/sudo -u $lgsmuser ./tmux-run 2>&1 | tac");
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
				if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['sysinfo'])) {
					$cmd = shell_exec("./script.sh sysinfo");
					while (@ob_end_flush());
						ini_set('implicit_flush', true);
						ob_implicit_flush(true);
						header("Content-type: text/plain");
						header('Cache-Control: no-cache'); // recommended to prevent caching of event data.
						for($i = 0; $i < 1000; $i++) {
							echo ' ';
						}
					ob_flush();
					flush();
					echo "<pre>$cmd</pre>";
					ob_flush();
					flush();
				}
			?>
		</div>
	</body>
</html>

