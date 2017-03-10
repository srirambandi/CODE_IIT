<?php
/*
 * code.iitd.ac.in
 * Sri Ram Bandi (srirambandi.654@gmail.com)
 *
 * The main page that lists all the problem
 */
	require_once('functions.php');
	if(!loggedin())
		header("Location: login.php");
	else
		include('header.php');
		// connectdb();
?>
              <li class="active"><a href="#">Problems</a></li>
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
        if(isset($_GET['success']))
          echo("<div class=\"alert alert-success\">\nCongratulations! You have solved the problem successfully.\n</div>");
        else if (isset($_GET['comments']) && $_GET['comments'] == 1) {
          # code...
          echo("<div class=\"alert alert-success\">\nComment successfully added.\n</div>");
        }
    ?>
    Below is a list of available problems for you to solve.<br/><br/>
      <ul class="nav nav-list">
        <li class="nav-header">AVAILABLE PROBLEMS</li>
        <?php
        	// list all the problems from the database
        	$query = "SELECT * FROM problems";
          $result = mysqli_query($db,$query);
          if(mysqli_num_rows($result)==0)
			       echo("<li>None</li>\n"); // no problems are there
		      else {
			      while($row = mysqli_fetch_array($result)) {
        				$sql = "SELECT status FROM solve WHERE (user_id='".$_SESSION['userid']."' AND problem_id='".$row['sl']."')";
        				$res = mysqli_query($db,$sql);
        				$tag = "";
        				// decide the attempted or solve tag
        				if(mysqli_num_rows($res) !== 0) {
        					$r = mysqli_fetch_array($res);
        					if($r['status'] == 1)
        						$tag = " <span class=\"label label-warning\">Attempted</span>";
        					else if($r['status'] == 2)
        						$tag = " <span class=\"label label-success\">Solved</span>";
        				}
        				if(isset($_GET['id']) and $_GET['id']==$row['sl']) {
        					$selected = $row;
        					echo("<li class=\"active\"><a href=\"#\">".$row['name'].$tag."</a></li>\n");
                } else
                    echo("<li><a href=\"index.php?id=".$row['sl']."\">".$row['name'].$tag."</a></li>\n");
            }
        	}
	      ?>
      </ul>
      <?php
        // if any problem is selected then list its details parsed by Markdown
      	if(isset($_GET['id'])) {
      		include('markdown.php');
      		$out1 = Markdown($selected['text']);
          $out2 = Markdown($selected['input']);
          $out3 = Markdown($selected['output']);
      		echo("<hr/>\n<h1>".$selected['name']."</h1>\n");
      		echo($out1);
          echo("<div style=\"background-color: #efefef;border: 1px solid #cccccc;padding-bottom: 2em;margin-bottom: 2em;width: 50%;overflow: auto;overflow-y: hidden;\"><table><tbody><tr><td style=\"padding: 0 1em 0 1em;\"><br><span>Input</span><br>&nbsp;</td><td style=\"padding: 0 1em 0 1em;\"><br><span>Output</span><br>&nbsp;</td></tr><tr><td style=\"padding: 0 1em 0 1em;\">
             <pre style=\"border: 0;background-color: #efefef;padding: 0;margin: 0;width: 100%;font-size: 100%;\">".$out2."
    </pre>
        </td>
        <td style=\"padding: 0 1em 0 1em;\">
             <pre style=\"border: 0;background-color: #efefef;padding: 0;margin: 0;width: 100%;font-size: 100%;\">".$out3."
    </pre>
        </td></tr></tbody></table>
        </div>");
      ?>
      <br/>
      <?php
        // number of people who have solved the problem
        $query = "SELECT * FROM solve WHERE(status=2 AND problem_id='".$selected['sl']."')";
        $result = mysqli_query($db,$query);
        $num = mysqli_num_rows($result);
        $query = "SELECT * FROM problems WHERE sl='".$_GET['id']."'";
        $result = mysqli_query($db,$query);
        $row = mysqli_fetch_array($result);
      ?>
      <form action="solve.php" method="get">
      <input type="hidden" name="id" value="<?php echo($selected['sl']);?>"/>
      <input class="btn btn-primary btn-large" type="submit" value="Solve"/> <span class="badge badge-info"><?php echo($num);?></span> have solved the problem.
      </form>
      <hr/>
      <div style="width: 60%">
      <div id="comments">
        <a href="#" onclick="$('#comments').load('update.php', {'action': 'show comments','id': '<?php echo $_GET['id']; ?>' });" title="Show comments">Show comments</a>
      </div>
      </br>
      </div>
      <div id="addcomment">
        <form method="post" action="update.php">
        <input type="hidden" name="action" value="add comments" id="action"/>
        <input type="hidden" name="sl" value="<?php echo $_GET['id']; ?>" id="action"/>
        <textarea style="font-family: mono;width: 50%;" rows="4" name="comment" id="comment" placeholder="Add comments here..."></textarea><br/>
        <input class="btn btn-primary" type="submit" value="Add comment"/>
        </form>
      </div>
      <?php
	}
      ?>
    </div> <!-- /container -->
<?php
	include('footer.php');
?>
<!-- border: 1px solid  #3366BB; -->
