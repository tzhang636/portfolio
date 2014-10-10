<?php

function addHeader($doc) {
	$lists = $doc->createElement("lists");
	$doc->appendChild($lists);
	
	$list = $doc->createElement("list");
	$list_node = $lists->appendChild($list);
	$list_node->setAttribute("path", "");

	return $list;
}

function addEntry($doc, $list, $e_kind, $e_name, $e_size, $e_revision, $e_author, $e_date) {
	$dir_entry = $doc->createElement("entry");
	$dir_entry_node = $list->appendChild($dir_entry);
	$dir_entry_node->setAttribute("kind", $e_kind);
	
	$name = $doc->createElement("name");
	$name->appendChild($doc->createTextNode($e_name));
	$dir_entry->appendChild($name);
	
	$size = $doc->createElement("size");
	$size->appendChild($doc->createTextNode($e_size));
	$dir_entry->appendChild($size);
	
	$commit = $doc->createElement("commit");
	$commit_node = $dir_entry->appendChild($commit);
	$commit_node->setAttribute("revision", $e_revision);
	
	$author = $doc->createElement("author");
	$author->appendChild($doc->createTextNode($e_author));
	$commit->appendChild($author);
	
	$date = $doc->createElement("date");
	$date->appendChild($doc->createTextNode($e_date));
	$commit->appendChild($date);
}

function outputXML($doc, $output_path) {
	echo $doc->saveXML();
	$doc->save($output_path);
}

$doc = new DOMDocument("1.0");
$doc->formatOutput = true;

$list = addHeader($doc);
addEntry($doc, $list, "dir", "Assignment0", "", "1", "zhang156", "2011");
addEntry($doc, $list, "file", "Assignment0/file.php", "333", "1", "zhang156", "2012");
outputXML($doc, "./testInput/listInput.xml");

?>