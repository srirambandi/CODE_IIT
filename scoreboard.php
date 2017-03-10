<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * Scoreboard page
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
              <li class="active"><a href="#">Scoreboard</a></li>
              <li><a href="account.php"><?php echo $_SESSION['username']; ?></a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
    The current standings of all the participants, the number of problems they have attempted and solved.
    <table class="table table-striped">
      <thead><tr>
        <th>Name</th>
        <th>Solved</th>
        <th>Attempted</th>
      </tr></thead>
      <tbody>
      <?php
        $query = "SELECT user_id, status FROM users WHERE user_id!='admin'";
        $result = mysqli_query($db,$query);
       	while($row = mysqli_fetch_array($result)) {
       		// displays the user, problems solved and attempted
       		$sql = "SELECT * FROM solve WHERE (status='2' AND user_id='".$row['user_id']."')";
       		$res = mysqli_query($db,$sql);
       		echo("<tr><td>".$row['user_id']." ");
       		if($row['status'] == 0) echo("</a> <span class=\"label label-important\">Banned</span>");
       		echo("</td><td><span class=\"badge badge-success\">".mysqli_num_rows($res));
       		$sql = "SELECT * FROM solve WHERE (status='1' AND user_id='".$row['user_id']."')";
       		$res = mysqli_query($db,$sql);
       		echo("</span></td><td><span class=\"badge badge-warning\">".mysqli_num_rows($res)."</span></td></tr>");
       	}
      ?>
      </tbody>
      </table>
    </div> <!-- /container -->

<?php
	include('footer.php');
?>
