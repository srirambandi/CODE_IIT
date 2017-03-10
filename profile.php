<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * Profileof the users
 */
	require_once('functions.php');
	if(!loggedin())
		header("Location: login.php");
	else
		include('header.php');
		connectdb();
?>
              <li><a href="index.php">Problems</a></li>
              <li><a href="submissions.php">Submissions</a></li>
              <li><a href="scoreboard.php">Scoreboard</a></li>
              <li><a href="account.php"><?php echo $_SESSION['username']; ?></a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
    <?php
      // get the name, email and status
      $query = "SELECT * FROM users WHERE user_id='".$_GET['uid']."'";
      $result = mysqli_query($db,$query);
      $row = mysqli_fetch_array($result);
    ?>
    <h1><small>Profile details for <?php echo($_GET['uid']); if($row['status'] == 0) echo(" <span class=\"label label-important\">Banned</span>");?></small></h1>
    Email: <?php echo($row['email']);?>
    <br/><br/>
    Details of problems attempted:
    <table class="table table-striped">
      <thead><tr>
        <th>Problem</th>
        <th>Attempts</th>
        <th>Status</th>
      </tr></thead>
      <tbody>
      <?php
        // list all the problems attempted or solved
        $query = "SELECT * FROM solve WHERE user_id='".$_GET['uid']."'";
        $result = mysqli_query($db,$query);
       	while($row = mysqli_fetch_array($result)) {
       		$sql = "SELECT name FROM problems WHERE sl=".$row['problem_id'];
       		$res = mysqli_query($db,$sql);
       		if(mysqli_num_rows($res) != 0) {
       			$field = mysqli_fetch_array($res);
	       		echo("<tr><td><a href=\"#\" onclick=\"$('#area').load('admin/preview.php', {action: 'code', uname: '".$_GET['uid']."', id: '".$row['problem_id']."', name: '".$field['name']."'});\">".$field['name']."</a></td><td><span class=\"badge badge-info\">".$row['attempts']);
       			if($row['status'] == 1)
       				echo("</span></td><td><span class=\"label label-warning\">Attempted</span></td></tr>\n");
       			else if($row['status'] == 2)
       				echo("</span></td><td><span class=\"label label-success\">Solved</span></td></tr>\n");
       		}
       	}
      ?>
      </tbody>
      </table>
      <div id="area"></div>
    </div> <!-- /container -->

<?php
	include('footer.php');
?>
