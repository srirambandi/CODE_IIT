<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * Account Settings page
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
              <li class="active"><a href="#"><?php echo $_SESSION['username']; ?></a></li>
              <li><a href="logout.php">Logout</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>
    </div>

    <div class="container">
    <?php
        if(isset($_GET['changed']))
          echo("<div class=\"alert alert-success\">\nAccount settings updated!\n</div>");
        else if(isset($_GET['passerror']))
          echo("<div class=\"alert alert-error\">\nThe old password you entered is wrong. Please enter the correct password and try again.\n</div>");
        else if(isset($_GET['derror']))
          echo("<div class=\"alert alert-error\">\nPlease enter all the details asked before you can continue!\n</div>");
    ?>
    Account settings for <?php echo($_SESSION['username']);?><hr/>
        <?php
            $query = "SELECT password FROM users WHERE user_id='".$_SESSION['userid']."'";
            $result = mysqli_query($db,$query);
            $fields = mysqli_fetch_array($result);
          ?>
    	  <form method="post" action="update.php">
          <input type="hidden" name="action" value="password"/>
          <h1><small>Change Password</small></h1>
          Old password: <input type="password" name="oldpass" value="<?php echo $fields['password'];?>;" /><br/>
          New password: <input type="password" name="newpass"/><br/><br/>
          <input class="btn" type="submit" name="submit" value="Change Password"/>
          </form>
          <hr/>

          <!-- <form method="post" action="update.php">
          <input type="hidden" name="action" value="email"/>
          <h1><small>Change Email</small></h1>
          <?php
          	// $query = "SELECT email FROM users WHERE username='".$_SESSION['username']."'";
          	// $result = mysqli_query($db,$query);
          	// $fields = mysqli_fetch_array($result);
          ?>
          Email: <input type="email" name="email" value="<?php
          // echo $fields['email'];?>"/><br/><br/>
          <input class="btn" type="submit" name="submit" value="Change Email"/>
          </form> -->
    </div> <!-- /container -->

<?php
	include('footer.php');
?>
