<?php

function addHeader($doc) {
	$log = $doc->createElement("log");
	$doc->appendChild($log);

	return $log;
}

function addLogEntry($doc, $log, $l_revision, $l_author, $l_date, 
					 $l_paths, $l_kind, $l_action, $l_msg) {
	$logentry = $doc->createElement("logentry");
	$logentry_node = $log->appendChild($logentry);
	$logentry_node->setAttribute("revision", $l_revision);
	
	$author = $doc->createElement("author");
	$author->appendChild($doc->createTextNode($l_author));
	$logentry->appendChild($author);
	
	$date = $doc->createElement("date");
	$date->appendChild($doc->createTextNode($l_date));
	$logentry->appendChild($date);
	
	$paths = $doc->createElement("paths");
	$logentry->appendChild($paths);
	
	for ($i=0; $i<sizeof($l_paths); $i++) {
		$path = $doc->createElement("path");
		$path->appendChild($doc->createTextNode($l_paths[$i]));
		$path_node = $paths->appendChild($path);
		$path_node->setAttribute("kind", $l_kind[$i]);
		$path_node->setAttribute("action", $l_action[$i]);
	}
	
	$msg = $doc->createElement("msg");
	$msg->appendChild($doc->createTextNode($l_msg));
	$logentry->appendChild($msg);
}

function outputXML($doc, $output_path) {
	echo $doc->saveXML();
	$doc->save($output_path);
}

$doc = new DOMDocument("1.0");
$doc->formatOutput = true;

$path = array();
$kind = array();
$action = array();

$path[0] = "/zhang156/Assignment0/file.php";
$kind[0] = "file";
$action[0] = "A";

$path[1] = "/zhang156/Assignment0";
$kind[1] = "dir";
$action[1] = "A";

$path2 = array();
$kind2 = array();
$action2 = array();

$path2[0] = "/zhang156/Assignment0/file.php";
$kind2[0] = "file";
$action2[0] = "M";

$log = addHeader($doc);
addLogEntry($doc, $log, "0", "zhang156", "2011", $path, $kind, $action, "initial commit");
addLogEntry($doc, $log, "1", "zhang156", "2012", $path2, $kind2, $action2, "changes to file.php");
outputXML($doc, "./testInput/logInput.xml");

?>