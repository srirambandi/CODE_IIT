<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * script that performs some database operations
 */
	include('functions.php');
	include('markdown.php');
	connectdb();
	if($_POST['action']=='email') {
		// change the email id of the user
		if(trim($_POST['email']) == "")
			header("Location: account.php?derror=1");
		else {
			mysql_query("UPDATE users SET email='".mysql_real_escape_string($_POST['email'])."' WHERE user_id='".$_SESSION['userid']."'");
			header("Location: account.php?changed=1");
		}
	} else if($_POST['action']=='password') {
		// change the password of the user
		if(trim($_POST['oldpass']) == "" or trim($_POST['newpass']) == "")
			header("Location: account.php?derror=1");
		else {
			$query = "SELECT salt,hash FROM users WHERE username='".$_SESSION['username']."'";
			$result = mysql_query($query);
			$fields = mysql_fetch_array($result);
			$currhash = crypt($_POST['oldpass'], $fields['salt']);
			if($currhash == $fields['hash']) {
				$salt = randomAlphaNum(5);
				$newhash = crypt($_POST['newpass'], $salt);
				mysql_query("UPDATE users SET hash='$newhash', salt='$salt' WHERE user_id='".$_SESSION['userid']."'");
				header("Location: account.php?changed=1");
			} else
				header("Location: account.php?passerror=1");
		}
	} else if($_POST['action'] == 'show comments') {
        $comments = Markdown($_POST['comments']);
        $id = $_POST['id'];
        $query = "SELECT * FROM problems WHERE sl='".$id."'";
        $result = mysqli_query($db,$query);
        $row = mysqli_fetch_array($result);
        $comments = Markdown($row['comments']);
        echo "<a href=\"#\" onclick=\"$('#comments').load('update.php', {'action': 'hide comments', 'id': '".$id."'});\" title=\"hide comments\">Hide comments</a>";
        echo("</br></br>");
        echo($comments);
    }  else if ($_POST['action'] == 'hide comments') {
    	# code...
    	$id = $_POST['id'];
    	$query = "SELECT * FROM problems WHERE sl='".$id."'";
        $result = mysqli_query($db,$query);
        $row = mysqli_fetch_array($result);
        // echo $_GET['id'];
    	echo "<a href=\"#\" onclick=\"$('#comments').load('update.php', {'action': 'show comments','id': '".$id."' });\" title=\"show comments\">Show comments</a>";
    }  else if ($_POST['action'] == 'add comments') {
        # code...
        $query = "SELECT * FROM problems WHERE sl='".$_POST['sl']."'";
        $result = mysqli_query($db,$query);
        $row = mysqli_fetch_array($result);
        $comments = $row['comments'];
        $comments.="\n\n[".$_SESSION['userid']."](http://localhost:8888/profile.php?uid=".$_SESSION['userid'].") \n >".$_POST['comment']."\n\n----";
        mysqli_query($db,"UPDATE problems SET comments = '".$comments."' WHERE sl='".$_POST['sl']."'");
        header("Location: index.php?id=".$_POST['sl']."#comments=1");
    }
?>
