<?php
require_once("../../../eclipse/simpletest/autorun.php");
require_once("../Insert.php");
require_once("../Connect.php");

class test extends UnitTestCase
{
	function testPostComment() {
		$table = "assignment_table";
		$query = "SELECT * FROM " . $table;
		$result = mysql_query($query);
		if (!$result) die("Could not connect: " . mysql_error());
		
		while ($row = mysql_fetch_array($result)) {
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "first comment";
			
			insertToDB($assignment, $parent_id, $comment);
			
			$table2 = "comment_table";
			$query2 = "SELECT * FROM " . $table2 . " WHERE assignment=" . "'$assignment'";
			$result2 = mysql_query($query2);
			if (!$result) die("Could not connect: " . mysql_error());
			
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "first comment");
			}
		}
	}
	
	function testReplyComment() {
		$table = "comment_table";
		$query = "SELECT * FROM " . $table;
		$result = mysql_query($query);
		if (!$result) die("Could not connect: " . mysql_error());
		
		while ($row = mysql_fetch_array($result)) {
			$assignment = $row["assignment"];
			$parent_id = $row["id"];
			$comment = "reply to first comment";
			
			insertToDB($assignment, $parent_id, $comment);
			
			$table2 = "comment_table";
			$query2 = "SELECT * FROM " . $table2 . " WHERE assignment=" . "'$assignment'" . " AND parent_id<>0";
			$result2 = mysql_query($query2);
			if (!$result2) die("Could not connect: " . mysql_error());
			
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["assignment"]);
				$this->assertTrue($row2["parent_id"] == $row["id"]);
				$this->assertTrue($row2["comment"] == "reply to first comment");
			}

			mysql_query("DELETE FROM comment_table WHERE assignment=" . "'$assignment'");
		}
	}
	
	function testSQLInjection() {
		$assignment = "Assignment0";
		$parent_id = 0;
		$comment = "this is an attack); 'DELETE FROM comment_table';";
		
		insertToDB($assignment, $parent_id, $comment);
		
		$table = "comment_table";
		$query = "SELECT * FROM " . $table;
		$result = mysql_query($query);
		if (!$result) die("Could not connect: " . mysql_error());
		
		while ($row = mysql_fetch_array($result)) {
			$this->assertTrue($row["comment"] == "this is an attack); 'DELETE FROM comment_table';");
		}
		
		mysql_query("DELETE FROM comment_table");
	}
	
	function testContentFilter() {
		$table = "assignment_table";
		$query = "SELECT * FROM " . $table;
		$result = mysql_query($query);
		if (!$result) die("Could not connect: " . mysql_error());
		
		while ($row = mysql_fetch_array($result)) {
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "fuck";
				
			insertToDB($assignment, $parent_id, $comment);
			
			$table2 = "comment_table";
			$query2 = "SELECT * FROM " . $table2 . " WHERE assignment=" . "'$assignment'";
			$result2 = mysql_query($query2);
			if (!$result) die("Could not connect: " . mysql_error());
				
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "f***");
			}
			
			mysql_query("DELETE FROM comment_table");
			
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "ass";
				
			insertToDB($assignment, $parent_id, $comment);
				
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "a**");
			}
			
			mysql_query("DELETE FROM comment_table");
				
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "shit";
			
			insertToDB($assignment, $parent_id, $comment);
			
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "s***");
			}
			
			mysql_query("DELETE FROM comment_table");
				
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "bitch";
			
			insertToDB($assignment, $parent_id, $comment);
			
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "b****");
			}
			
			mysql_query("DELETE FROM comment_table");
				
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "prick";
			
			insertToDB($assignment, $parent_id, $comment);
			
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "p****");
			}
			
			mysql_query("DELETE FROM comment_table");
			
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "gay";
				
			insertToDB($assignment, $parent_id, $comment);
				
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "g**");
			}
			
			mysql_query("DELETE FROM comment_table");
			
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "damn";
				
			insertToDB($assignment, $parent_id, $comment);
				
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "d***");
			}
			
			mysql_query("DELETE FROM comment_table");
			
			$assignment = $row["title"];
			$parent_id = 0;
			$comment = "fag";
				
			insertToDB($assignment, $parent_id, $comment);
				
			while ($row2 = mysql_fetch_array($result2)) {
				$this->assertTrue($row2["assignment"] == $row["title"]);
				$this->assertTrue($row2["parent_id"] == 0);
				$this->assertTrue($row2["comment"] == "f**");
			}
			
			mysql_query("DELETE FROM comment_table");
		}
	}
}
?>