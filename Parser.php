<?php
require_once("Connect.php");
require("Project.php");
require("File.php");
require("Version.php");

/**
 * 
 * Parses an svn_list and svn_log .xml file and produces an 
 * output .xml file that formats the relevant information neatly.
 * This class contains three fields:
 * 	$projects - an array of Project objects
 * 	$list_doc - the DOMDocument representing svn_list.xml
 * 	$log_doc - the DOMDocument representing svn_log.xml
 * @author Tom Zhang
 *
 */
class Parser
{
	public $projects;
	public $list_doc;
	public $log_doc;
	
	/**
	 * 
	 * Initializes the member variables.
	 */
	function __construct() {
		$this->projects = array();
		$this->list_doc = new DOMDocument("1.0");
		$this->log_doc = new DOMDocument("1.0");
	}
	
	/**
	 * 
	 * Parses svn_list and svn_log .xml files.
	 * @param unknown_type $list_path - the path to svn_list.xml
	 * @param unknown_type $log_path - the path to svn_log.xml
	 */
	function parse($list_path, $log_path) {
		$this->parseList($list_path);
		$this->parseLog($log_path);
	}
	
	/**
	 * 
	 * Writes the data structure to an output .xml file.
	 * Transforms the output .xml file into an .html file.
	 * @param unknown_type $output_path - the output file path
	 */
	function output($output_path) {
		$doc = new DOMDocument("1.0");
		$doc->formatOutput = true;
		
		$svn_dump = $doc->createElement("SVNDump");
		$doc->appendChild($svn_dump);

		foreach ($this->projects as $project) {
			$project_element = $doc->createElement("project");
			$this->createProjectElements($doc, $project, $project_element);
			
			$files = $doc->createElement("files");
			$project_element->appendChild($files);
			
			foreach ($project->files as $file) {
				$file_element = $doc->createElement("file");
				$this->createFileElements($doc, $file, $file_element);
				
				$versions = $doc->createElement("versions");
				$file_element->appendChild($versions);
				
				foreach ($file->versions as $version) {
					$version_element = $doc->createElement("version");
					$this->createVersionElements($doc, $version, $version_element);
					
					$versions->appendChild($version_element);
				}
				$files->appendChild($file_element);
			}
			$svn_dump->appendChild($project_element);
		}
		$doc->save($output_path);
		$this->loadToPortfolio($doc, "./style/Portfolio.xsl");
		$this->addToDatabase("assignment_table");
	}
	
	/**
	 * 
	 * Parses svn_list.xml and extracts its contents into the data structure.
	 * @param unknown_type $list_path - the path to svn_list.xml
	 */
	function parseList($list_path) {
		$this->list_doc->load($list_path);
		$entries = $this->list_doc->getElementsByTagName("entry");
		
		foreach ($entries as $entry) {
			$name = $this->getNodeValue($entry, "name");
			$date = $this->getNodeValue($entry, "date");
			
			if ($entry->getAttribute("kind") == "dir") { // directory
				if ($this->isProjectTitle($name)) {
					$this->createProject($name);
					$this->setProjectFields($name, $date);
				}
			}
			else { // file
				if ($this->containsAssignment($name)) {
					$size = $this->getNodeValue($entry, "size");
					$doctype = $this->getDoctype($name);
					$path = $this->buildFilePath($name);
				
					$title = $this->getTitle($name);
					$this->createFile($title, $path);
					$this->setFileFields($title, $path, $size, $doctype);
				}
			}
		}
	}

	/**
	 * 
	 * Parses svn_log.xml and extracts its contents into the data structure.
	 * @param unknown_type $log_path - the the path to svn_log.xml
	 */
	function parseLog($log_path) {
		$this->log_doc->load($log_path);
		$log_entries = $this->log_doc->getElementsByTagName("logentry");
		
		foreach ($log_entries as $log_entry) {
			$first_path = $this->getNodeValue($log_entry, "path");
			
			if ($this->containsAssignment($first_path)) {
				$number = $log_entry->getAttribute("revision");
				$author = $this->getNodeValue($log_entry, "author");
				$info = $this->getNodeValue($log_entry, "msg");
				$date = $this->getNodeValue($log_entry, "date");
				
				// starts at 1.0
				$title = $this->getTitle($first_path);
				$old_version = $this->projects[$title]->version;
				
				if (!$this->isProjectSummarySet($title))
					$this->setProjectSummary($title, $info);
				
				$paths = $log_entry->getElementsByTagName("path");
				foreach ($paths as $path) {
					if ($this->pathCommitted($path) && $this->projVersionIncremented($title, $old_version))
						$this->incrementProjVersion($title);
					
					if ($this->isFile($path)) {
						$file_path = $path->nodeValue;
						if (array_key_exists($file_path, $this->projects[$title]->files)) {
							$this->createVersion($title, $file_path, $number);
							$this->setVersion($title, $file_path, $number, $author, $info, $date);
						}
					}
				}
			}
		}
	}
	
	/**
	 * 
	 * Creates a new DomDocument and loads in an XSL file.
	 * Formats the original XML file ($doc) using an XSL file.
	 * @param unknown_type $doc - the original (formatted) XML file 
	 * @param unknown_type $file - the path of the XSL file
	 */
	function loadToPortfolio($doc, $file) {
		$xsl = new DomDocument();
		$xsl->load($file);
		$proc = new XsltProcessor();
		$xsl = $proc->importStylesheet($xsl);
		$newdom = $proc->transformToDoc($doc);
		echo $newdom->saveHTML();
	}
	
	/**
	 * 
	 * Adds all the projects from the project array into the database.
	 * Checks for duplicates before insertion.
	 * @param unknown_type $table - the table in the database to insert to
	 */
	function addToDatabase($table) {
		$query = "SELECT * FROM " . $table;
		$result = mysql_query($query);
		$in_db = false;
		
		foreach ($this->projects as $project) {
			while ($row = mysql_fetch_array($result)) {
				if ($project->title == $row["title"])
					$in_db = true;
			}
			
			if (!$in_db) {
				$insert_cmd = "INSERT INTO ". $table . " (id, title) VALUES ('', '$project->title')";
				mysql_query($insert_cmd) or die("Error: " . mysql_error());
			}
		}
	}
	
	/**
	 * 
	 * Creates a project with $title as its title in the array.
	 * @param unknown_type $title - the title of the project
	 */
	function createProject($title) {
		$this->projects[$title] = new Project();
	}
	
	/**
	 * 
	 * Sets the title and date fields of a project in the array.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $date - the date of the most recent commit of that project
	 */
	function setProjectFields($title, $date) {
		$this->projects[$title]->title = $title;
		$this->projects[$title]->date = $date;
	}
	
	/**
	 * 
	 * Creates a file inside a project with $title as its title.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $path - the path name of the file
	 */
	function createFile($title, $path) {
		$this->projects[$title]->files[$path] = new File();
	}
	
	/**
	 * 
	 * Sets the path, size, and doctype of a file in a project.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $path - the path name of the file
	 * @param unknown_type $size - the size of the file
	 * @param unknown_type $doctype - the type of the file
	 */
	function setFileFields($title, $path, $size, $doctype) {
		$this->projects[$title]->files[$path]->size = $size;
		$this->projects[$title]->files[$path]->doctype = $doctype;
		$this->projects[$title]->files[$path]->path = $path;
	}
	
	/**
	 * 
	 * Creates a version inside a file (with $file_path as its path)
	 * that is inside a project (with $title as its title).
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $file_path - the path name of the file
	 * @param unknown_type $number - the revision number
	 */
	function createVersion($title, $file_path, $number) {
		$this->projects[$title]->files[$file_path]->versions[$number] = new Version();
	}
	
	/**
	 * 
	 * Sets the number, author, info, and date of a version inside a file inside a project.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $file_path - the path name of the file
	 * @param unknown_type $number - the revision number
	 * @param unknown_type $author - the author who committed the revision
	 * @param unknown_type $info - the revision message, if any
	 * @param unknown_type $date - the date of the commit
	 */
	function setVersion($title, $file_path, $number, $author, $info, $date) {
		$this->projects[$title]->files[$file_path]->versions[$number]->number = $number;
		$this->projects[$title]->files[$file_path]->versions[$number]->author = $author;
		$this->projects[$title]->files[$file_path]->versions[$number]->info = $info;
		$this->projects[$title]->files[$file_path]->versions[$number]->date = $date;
	}
	
	/**
	 * 
	 * Sets the summary field of a project. Also sets the 
	 * summary_set field to true afterwards.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $info - the commit msg to set
	 */
	function setProjectSummary($title, $info) {
		$this->projects[$title]->summary = $info;
		$this->projects[$title]->summary_set = true;
	}
	
	/**
	 * 
	 * Checks to see if project summary is set.
	 * @param unknown_type $title - the title of the project
	 * @return whether the project summary is set
	 */
	function isProjectSummarySet($title) {
		return $this->projects[$title]->summary_set;
	}
	
	/**
	 * 
	 * Checks whether name contains "Assignment" as a substring.
	 * @param unknown_type $name - string to check
	 * @return false when "Assignment" is not a substring
	 */
	function containsAssignment($name) {
		return strstr($name, "Assignment");
	}
	
	/**
	 * 
	 * Checks to see if name is a project title.
	 * @param unknown_type $name - string to check
	 * @return boolean - whether name is a project title
	 */
	function isProjectTitle($name) {
		return strstr($name, "Assignment") && !strstr($name, "/");
	}
	
	/**
	 * 
	 * Checks to see if a path is a file.
	 * @param unknown_type $path_tag - the tag to check
	 * @return boolean - whether the path is a file
	 */
	function isFile($path_tag) {
		return $path_tag->getAttribute("kind") == "file";
	}
	
	/**
	 * 
	 * Checks to see if a path is committed.
	 * @param unknown_type $path_tag - the tag to check
	 * @return boolean - whether the path is committed
	 */
	function pathCommitted($path_tag) {
		return $path_tag->getAttribute("action") == "M";
	}
	
	/**
	 * 
	 * Checks whether the project version has been incremented.
	 * @param unknown_type $title - the title of the project
	 * @param unknown_type $old_version - the old_version
	 * @return boolean - whether the project version is the same 
	 * as the old version
	 */
	function projVersionIncremented($title, $old_version) {
		return $this->projects[$title]->version == $old_version;
	}
	
	/**
	 * 
	 * Increments the project version
	 * @param unknown_type $title - the title of the project
	 */
	function incrementProjVersion($title) {
		$this->projects[$title]->version += 0.1;
	}
	
	/**
	 * 
	 * Obtains the text field inside a specific tag.
	 * @param unknown_type $entry - The root tag to search in
	 * @param unknown_type $name - The tag to search for in entry
	 * @return NULL|string - NULL if no text in the tag, 
	 * 						 otherwise a string represnting the text field
	 */
	function getNodeValue($entry, $name) {
		$list = $entry->getElementsByTagName($name);
		if ($list->length == 0)
			return null;
		else 
			return trim($list->item(0)->nodeValue);
	}
	
	/**
	 * 
	 * Obtains the project title from a string.
	 * @param unknown_type $name - the string to extract the project name from
	 * @return string - the project title
	 */
	function getTitle($name) {
		$title = strtok($name, "/");
		while (!strstr($title, "Assignment"))
			$title = strtok("/");
		return trim($title);	
	}
	
	/**
	 * 
	 * Obtains the file name from a string.
	 * @param unknown_type $name - the string to extract the file name from
	 * @return string - the file name
	 */
	function getFileName($name) {
		$file_name = strtok($name, "/");
		while ($file_name != false) {
			$temp_file_name = $file_name;
			$file_name = strtok("/");
			if ($file_name == false)
				return trim($temp_file_name);
		}
	}
	
	/**
	 * 
	 * Obtains the file extension from a file name
	 * @param unknown_type $file_name - the string to extract the file extension from
	 * @return string - the file extension
	 */
	function getFileExt($file_name) {
		$file_ext = strtok($file_name, "/.");
		while ($file_ext != false) {
			$temp_file_ext = $file_ext;
			$file_ext = strtok("/.");
			if ($file_ext == false)
				return trim($temp_file_ext);
		}
	}
	
	/**
	 * 
	 * Builds a file path from a given name in svn_list.xml
	 * @param unknown_type $name - a name field from svn_list.xml
	 * @return string - the completed file path
	 */
	function buildFilePath($name) {
		return trim("/zhang156/" . $name);
	}
	
	/**
	 * 
	 * Returns a doctype of the file depending on the file name and extension
	 * @param unknown_type $name - the name from svn_list.xml
	 * @return string - the assigned doctype
	 */
	function getDoctype($name) {
		$file_name = $this->getFileName($name);
		$file_ext = $this->getFileExt($file_name);
		
		if (strstr($file_name, "Test"))
			return "test";
		else {
			if ($file_ext == "c" || $file_ext == "java" || 
				$file_ext == "py" || $file_ext == "php")
				return "code";
			else if ($file_ext == "bmp")
				return "image";
			else if ($file_ext == "json" || $file_ext == "xml")
				return "data";
			else
				return "documentation";
		}
	}
	
	/**
	 * 
	 * Creates elements out of the fields inside a project 
	 * and writes them to a DOMDocument.
	 * @param unknown_type $doc - the DOMDocument to write to
	 * @param unknown_type $project - the project object to get the fields from
	 * @param unknown_type $project_element - the project element to append to
	 */
	function createProjectElements($doc, $project, $project_element) {
		$title = $doc->createElement("title");
		$title->appendChild($doc->createTextNode($project->title));
		$project_element->appendChild($title);
			
		$date = $doc->createElement("date");
		$date->appendChild($doc->createTextNode($project->date));
		$project_element->appendChild($date);
			
		$version = $doc->createElement("version");
		$version->appendChild($doc->createTextNode($project->version));
		$project_element->appendChild($version);
			
		$summary = $doc->createElement("summary");
		$summary->appendChild($doc->createTextNode($project->summary));
		$project_element->appendChild($summary);
	}
	
	/**
	 * 
	 * Creates elements out of fields inside a file
	 * and writes them to a DOMDocument.
	 * @param unknown_type $doc - the DOMDocument to write to
	 * @param unknown_type $file - the file object to get the fields from
	 * @param unknown_type $file_element - the file element to append to
	 */
	function createFileElements($doc, $file, $file_element) {
		$size = $doc->createElement("size");
		$size->appendChild($doc->createTextNode($file->size));
		$file_element->appendChild($size);
	
		$doctype = $doc->createElement("doctype");
		$doctype->appendChild($doc->createTextNode($file->doctype));
		$file_element->appendChild($doctype);
	
		$path = $doc->createElement("path");
		$path->appendChild($doc->createTextNode($file->path));
		$file_element->appendChild($path);
	}
	
	/**
	 * 
	 * Creates elements out of fields inside a version
	 * and writes them to a DOMDocument.
	 * @param unknown_type $doc - the DOMDocument to write to
	 * @param unknown_type $version - the version object to get the fields from
	 * @param unknown_type $version_element - the version element to append to
	 */
	function createVersionElements($doc, $version, $version_element) {
		$number = $doc->createElement("number");
		$number->appendChild($doc->createTextNode($version->number));
		$version_element->appendChild($number);
	
		$author = $doc->createElement("author");
		$author->appendChild($doc->createTextNode($version->author));
		$version_element->appendChild($author);
	
		$info = $doc->createElement("info");
		$info->appendChild($doc->createTextNode($version->info));
		$version_element->appendChild($info);
	
		$date = $doc->createElement("date");
		$date->appendChild($doc->createTextNode($version->date));
		$version_element->appendChild($date);
	}
}
?>