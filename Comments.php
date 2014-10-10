<html>

<head>
<link rel="stylesheet" type="text/css" href="./style/MyStyle.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="Comments.js"></script>
</head>

<body>

<!-- Header -->
<div id="hdr">
	<br/>
	<img src="./style/logo.bmp" alt="Logo" width="80" height="80" />
		<h1>CS 242: Programming Studio</h1>
		<h2>University of Illinois at Urbana-Champaign</h2>
</div>
<!-- end of header -->

<!-- left column -->
<div id="lh-col">	
<a href="javascript:void(0);" class="homepage">Back to homepage</a>
<br/>
<?php
require_once("Connect.php");

/**
 * 
 * Displays the comments of every project in the database.
 * Note that the while loop displays the titles along with
 * the forms and calls displayChildren to display all the 
 * child comments of that project.
 */
$table = "assignment_table";
$query = "SELECT * FROM " . $table;
$result = mysql_query($query);

$id = 0;
while ($row = mysql_fetch_array($result)) {
	echo "<div class=accordionButton>" . $row["title"] . "</div>";
	echo "<div class=accordionContent>";
	echo "<ul id=a>";
		echo "<a name=" . $row["title"] . ">";
		echo "<a href=# onclick=toggle_visibility(" . $id . ");>Leave comment</a><br/><br/>";
		echo "<div id=" . $id . " style=display:none;>";
		$parent_id = 0;
		echoForm($row["title"], $parent_id);
		echo "</div>";
		$id = $id + 1;

		displayChildren($row["title"], 0);
		
	echo "</ul>";
	echo "</div>";
}

/**
 * 
 * Recursively displays the child comments of $assignment. 
 * @param unknown_type $assignment - the assignment whose comments are to be displayed
 * @param unknown_type $parent_id - the parent_id of the comments that are to be displayed
 */
function displayChildren($assignment, $parent_id) {
	$query = "SELECT * FROM comment_table WHERE assignment=" ."'$assignment'". " AND parent_id=" . $parent_id;
	$result = mysql_query($query);
	
	while ($row = mysql_fetch_array($result)) {
		echo "<ul id=b>";
			echo "<li>";
			echo $row["comment"] . "<br/>";
			echo "<a href=# onclick=toggle_visibility(".$row["id"].");>Reply</a><br/><br/>";
			echo "<div id=" .$row["id"]. "  style=display:none;>";
			echoForm($assignment, $row["id"]);
			echo "</div>";
			
			displayChildren($assignment, $row["id"]);
			
			echo "</li>";
		echo "</ul>";
	}
}

/**
 * 
 * Displays and initiates the comment forms.
 * @param unknown_type $assignment - the assignment of the comment inserted through this form
 * @param unknown_type $parent_id - the parent id of every new comment inserted through this form
 */
function echoForm($assignment, $parent_id) {
	$id = str_replace(".", "0", $assignment);
	$id = $id . $parent_id;
	echo "<form name=" . $id . " method=post>";
	echo "Comment (250 characters or less): <br/>";
	echo "<textarea type=text name=comment id=comment" . $id . " class=inputtext wrap=physical></textarea>";
	echo "<input type=hidden name=parent_id id=parent_id" . $id . " value=" . $parent_id . " />";
	echo "<input type=hidden name=assignment id=assignment" . $id . " value=" . $assignment . " />";
	echo "<input type=submit class=comment_submit id=" . $id . " value=Submit />";
	echo "</form>";
}
?>	
</div>
<!-- end of left column -->

<!-- right column -->
<div id="rh-col"><br />
<h3>Project Comments</h3>
<?php
/**
 * 
 * Displays the links to the comments of each project.
 */
$table = "assignment_table";
$query = "SELECT * FROM " . $table;
$result = mysql_query($query);

while ($row = mysql_fetch_array($result)) {
	echo "<ul id=a>";
		echo "<li>";
		echo "<b><a href=#" . $row["title"] . ">" . $row["title"] . "</a></b>";
		echo "<br/>";
	echo "</ul>";
}
?>
</div>
<!-- end of right column -->

</body>
</html>