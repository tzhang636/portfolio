<?php
require_once("Connect.php");

/**
 * 
 * Checks for comment conditions ( > 250 chars, empty string). 
 * Then passes it through functions checkInput and checkContent
 * for content filtering.
 * @param unknown_type $assignment - the assignment of the inserted comment
 * @param unknown_type $parent_id - the parent id of the inserted comment
 * @param unknown_type $comment - the comment that is inserted
 */
function insertToDB($assignment, $parent_id, $comment) {
	if (strlen($comment) > 250) {
		echo "Please limit your submission to 250 characters or less.<br/>";
	}

	else if (strlen($comment) == 0) {
		echo "No comment entered. Please try again.<br/>";
	}

	else {
		$parent_id = checkInput($parent_id);
		$assignment = checkInput($assignment);
		$comment = checkInput($comment);
		$comment = checkContent($comment);

		$table = "comment_table";
		$insert_cmd = "INSERT INTO " . $table . " (id, parent_id, assignment, comment)
						VALUES ('', $parent_id, $assignment, $comment)";
		mysql_query($insert_cmd) or die("Error: " . mysql_error());
		echo "Submission successful. Your submission was \" " . $comment . " \".<br/>";
	}
}

/**
 * 
 * Checks and modifies the input string for slashes and escape characters.
 * Makes the string safe for insertion into the database.
 * @param unknown_type $value - the string to check
 * @return string - the string after formatting
 */
function checkInput($value) {
	if (get_magic_quotes_gpc()) {
		$value = stripslashes($value);
	}

	if (!is_numeric($value)) {
		$value = "'" . mysql_real_escape_string($value) . "'";
	}

	return $value;
}

/**
 * 
 * Checks and modifies the input string for profanity.
 * Makes the string safe for insertion into the database.
 * @param unknown_type $value - the string to check
 * @return string - the string after formatting
 */
function checkContent($value) {
	$prof = array();
	$repl = array();

	$prof[0] = "/ass+/i";
	$prof[1] = "/f+u+c+k+/i";
	$prof[2] = "/bitch/i";
	$prof[3] = "/prick/i";
	$prof[4] = "/shit+/i";
	$prof[5] = "/gay+/i";
	$prof[6] = "/damn+/i";
	$prof[7] = "/fag+/i";

	$repl[0] = "a**";
	$repl[1] = "f***";
	$repl[2] = "b****";
	$repl[3] = "p****";
	$repl[4] = "s***";
	$repl[5] = "g**";
	$repl[6] = "d***";
	$repl[7] = "f**";

	$value = preg_replace($prof, $repl, $value);

	return $value;
}
?>