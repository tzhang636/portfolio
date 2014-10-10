<?php

$parser = new Parser();

$svn_log_file = "svn_log.xml";
$log_file = fopen($svn_log_file, "r");
$log_data = fread($log_file, filesize($svn_log_file));

$svn_list_file = "svn_list.xml";
$list_file = fopen($svn_list_file, "r");
$list_data = fread($list_file, filesize($svn_list_file));

$parser->parse($log_data, $list_data);

xml_parser_free($parser->log_parser);
xml_parser_free($parser->list_parser);
fclose($log_file);
fclose($list_file);


$titles = $this->list_doc->getElementsByTagName("name");
foreach ($titles as $title)
{
	$proj_title = $title->nodeValue;
	if (!strstr($proj_title, "/") && strstr($proj_title, "Assignment"))
	{
		$this->projects[$proj_title] = new Project();
		$this->projects[$proj_title]->title = $proj_title;
	}
}

foreach ($parser->projects as $project)
{
	echo $project->title;
}

foreach ($parser->projects as $project)
{
	echo "project title: " . $project->title . "<br>";
	echo "project date: " . $project->date . "<br>";
	echo "project version: " . $project->version . "<br>";
	echo "project summary: " . $project->summary . "<br>";
	echo "<br>";
	foreach ($project->files as $file)
	{
		echo "file size: " . $file->size . "<br>";
		echo "file doctype: " . $file->doctype . "<br>";
		echo "file path: " . $file->path . "<br>";
		echo "<br>";
		foreach ($file->versions as $version)
		{
			echo "version number: " . $version->number . "<br>";
			echo "version author: " . $version->author . "<br>";
			echo "version info: " . $version->info . "<br>";
			echo "version date: " . $version->date . "<br>";
			echo "<br>";
		}
	}
	echo "<br>";
}

?>