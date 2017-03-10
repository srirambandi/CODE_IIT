<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * PHP script that returns for AJAX requests
 */
	if($_POST['action'] == 'preview') {
		// preview for the markdown problem statement
		if($_POST['title']=="" and $_POST['text']=="")
			echo("<div class=\"alert alert-error\">You have not entered either the title or the problem text!</div>");
		else {
			include('../markdown.php');
			$out1 = Markdown($_POST['text']);
			$out2 = Markdown($_POST['input']);
			$out3 = Markdown($_POST['output']);
			echo("<hr/>\n<h1 position=\"center\">".$_POST['title']."</h1>\n");
			echo($out1);
			echo("<div style=\"background-color: #efefef;border: 1px solid #cccccc;padding-bottom: 2em;margin-bottom: 2em;width: 50%;overflow: auto;overflow-y: hidden;\"><table><tbody><tr><td style=\"padding: 0 1em 0 1em;\"><br><span>Input</span><br>&nbsp;</td><td style=\"padding: 0 1em 0 1em;\"><br><span>Output</span><br>&nbsp;</td></tr><tr><td style=\"padding: 0 1em 0 1em;\">
				<pre style=\"border: 0;background-color: #efefef;padding: 0;margin: 0;width: 100%;font-size: 100%;\">".$_POST['input']."
	</pre>
				</td>
				<td style=\"padding: 0 1em 0 1em;\">
				<pre style=\"border: 0;background-color: #efefef;padding: 0;margin: 0;width: 100%;font-size: 100%;\">".$_POST['output']."
	</pre>
				</td></tr></tbody></table>
				</div>");
		}
	} else if($_POST['action'] == 'code' and is_numeric($_POST['id'])) {
		// formatting for codes
		include('../functions.php');
		connectdb();
		echo("<hr/><h1><small>".$_POST['name']."</small></h1>\n");
		$query = "SELECT filename, soln FROM solve WHERE (user_id='".mysqli_real_escape_string($db,$_POST['uname'])."' AND problem_id='".$_POST['id']."')";
		$result = mysqli_query($db,$query);
		$row = mysqli_fetch_array($result);
		$str = str_replace("<", "&lt;", $row['soln']);
		echo("<strong>".$row['filename']."</strong><br/><br/>\n<pre>".str_replace(">", "&gt;", $str)."</pre>");
	}
?>
