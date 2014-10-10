<?php

/**
 * 
 * Represents a version object with four fields:
 * 	$number - the revision number
 * 	$author - the author that committed the file version
 * 	$info - the commit message for the file version
 * 	$date - the commit date
 * @author Tom Zhang
 *
 */
class Version
{
	public $number;
	public $author;
	public $info;
	public $date;
	
	/**
	 * 
	 * Initializes the version fields.
	 */
	function __construct() {
		$this->number = 0;
		$this->author = null;
		$this->info = null;
		$this->date = null;
	}
}

?>