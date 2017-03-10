<?php
ini_set("auto_detect_line_endings", true);
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * Compiler PHP Script
 */
	require_once('functions.php');
	// include('dbinfo.php');
	connectdb();
	$query = "SELECT * FROM contests";
        $result = mysqli_query($db,$query);
        $accept = mysqli_fetch_array($result);
        $query = "SELECT status FROM users WHERE user_id='".$_SESSION['userid']."'";
        $result = mysqli_query($db,$query);
        $status = mysqli_fetch_array($result);
	if (!preg_match("/^[^\\/?* :;{}\\\\]+\\.[^\\/?*: ;{}\\\\]{1,4}$/", $_POST['filename']))
		header("Location: solve.php?ferror=1&id=".$_POST['id']); // invalid filename
        // check if the user is banned or allowed to submit and SQL Injection checks
        else if($accept['accept'] == 1 and $status['status'] == 1 and is_numeric($_POST['id'])) {
        	$soln = mysqli_real_escape_string($db,$_POST['soln']);
        	$filename = mysqli_real_escape_string($db,$_POST['filename']);
        	$lang = mysqli_real_escape_string($db,$_POST['lang']);
        	//check if entries are empty
        	if(trim($soln) == "" or trim($filename) == "" or trim($lang) == "")
        		header("Location: solve.php?derror=1&id=".$_POST['id']);
        	else {
			if($_POST['ctype']=='new')
				// add to database if it is a new submission
				$query = "INSERT INTO `solve` ( `problem_id` , `user_id`, `soln`, `filename`, `lang`) VALUES ('".$_POST['id']."', '".$_SESSION['userid']."', '".$soln."', '".$filename."', '".$lang."')";
			else {
				// update database if it is a re-submission
				$tmp = "SELECT attempts FROM solve WHERE (problem_id='".$_POST['id']."' AND user_id='".$_SESSION['user_id']."')";
				$result = mysqli_query($db,$tmp);
				$fields = mysqli_fetch_array($result);
				$query = "UPDATE solve SET lang='".$lang."', attempts='".($fields['attempts']+1)."', soln='".$soln."', filename='".$filename."' WHERE (user_id='".$_SESSION['user_id']."' AND problem_id='".$_POST['id']."')";
			}
			mysqli_query($db,$query);
			// connect to the java compiler server to compile the file and fetch the results
			$socket = fsockopen($compilerhost, $compilerport,$errno,$errstr);
			// echo $socket;
			if($socket) {
				// $server_response = fread($socket, 256);
				// echo $server_response;
				// fwrite($socket, $_POST['filename']."\n");
				// $query = "SELECT time, input, output FROM problems WHERE sl='".$_POST['id']."'";
				$query = "SELECT problem_id FROM problems WHERE sl='".$_POST['id']."'";
				$result = mysqli_query($db,$query);
				$fields = mysqli_fetch_array($result);
				// fwrite($socket, $fields['time']."\n");
				// $soln = str_replace("\n", '$_n_$', treat($_POST['soln']));
				// fwrite($socket, $soln."\n");
				// $input = str_replace("\n", '$_n_$', treat($fields['input']));
				// fwrite($socket, $input."\n");
				// fwrite($socket, $lang."\n");
				fwrite($socket, $_SESSION['userid']."\n");
				fwrite($socket, $fields['problem_id']."\n");
				$status = fgets($socket);
				// $contents = "";
				// while(!feof($socket))
				// 	$contents = $contents.fgets($socket);
				// if($status == 0) {
				// 	// oops! compile error
				// 	$query = "UPDATE solve SET status=1 WHERE (user_id='".$_SESSION['userid']."' AND problem_id='".$_POST['id']."')";
				// 	mysqli_query($db,$query);
				// 	$_SESSION['cerror'] = trim($contents);
				// 	header("Location: solve.php?cerror=1&id=".$_POST['id']);
				// } else if($status == 1) {
				// 	if(trim($contents) == trim(treat($fields['output']))) {
				// 		// holla! problem solved
				// 		$query = "UPDATE solve SET status=2 WHERE (username='".$_SESSION['username']."' AND problem_id='".$_POST['id']."')";
				// 		mysqli_query($db,$query);
				// 		header("Location: index.php?success=1");
				// 	} else {
				// 		// duh! wrong output
				// 		$query = "UPDATE solve SET status=1 WHERE (username='".$_SESSION['username']."' AND problem_id='".$_POST['id']."')";
				// 		mysqli_query($db,$query);
				// 		header("Location: solve.php?oerror=1&id=".$_POST['id']);
				// 	}
				// } else if($status == 2) {
				// 	$query = "UPDATE solve SET status=1 WHERE (username='".$_SESSION['username']."' AND problem_id='".$_POST['id']."')";
				// 	mysqli_query($db,$query);
				// 	header("Location: solve.php?terror=1&id=".$_POST['id']);
				// }
			} else
				header("Location: solve.php?serror=1&id=".$_POST['id']); // compiler server not running
		}
	}
?>
