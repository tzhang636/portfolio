<?php

/**
 * 
 * Represents a project object with five fields:
 * 	$title - the title of the project
 * 	$date - the date of the last commit for the project
 * 	$version - starts at 1.0 for the first commit,
 * 			   add 0.1 for each additional commit
 * 	$summary - the most recent commit message for the assignment
 * 	$summary_set - set to true if summary is set
 * 	$files - an array of file objects inside the project
 * 
 * @author Tom Zhang
 *
 */
class Project
{
	public $title;
	public $date;
	public $version;
	public $summary;
	public $summary_set;
	public $files;
	
	/**
	 * 
	 * Initializes the project fields.
	 */
	function __construct() {
		$this->title = null;
		$this->date = null;
		$this->version = 1.0;
		$this->summary = null;
		$this->summary_set = false;
		$this->files = array();
	}
}

?>