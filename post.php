<?php
	include('config.php');	
	ini_set('max_execution_time','1440M');
	$rconcmd = $_POST["rconcmd"];
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
	/*if($_SERVER['REQUEST_METHOD'] == "POST" and isset($_POST['update'])) {
		$cmd = "/usr/bin/sudo -u $lgsmuser /home/$lgsmuser/$game update 2>&1";
		while (@ ob_end_flush()); // end all output buffers if any
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
