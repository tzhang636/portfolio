<?php

/**
 * 
 * Represents a File object with four fields:
 * 	$size - the size of the file, in bytes
 * 	$doctype - the type of the file
 * 	$path - the path of the file in the svn hierarchy
 * 	$versions - an array of version objects in the file
 * @author Tom Zhang
 *
 */
class File
{
	public $size;
	public $doctype;
	public $path;
	public $versions;
	
	/**
	 * 
	 * Initializes the file fields.
	 */
	function __construct() {
		$this->size = 0;
		$this->doctype = null;
		$this->path = null;
		$this->versions = array();
	}
}

?>