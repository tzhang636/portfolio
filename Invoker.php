<?php

require("Parser.php");

$parser = new Parser();
$parser->parse("./input/svnList.xml", "./input/svnLog.xml");
$parser->output("./output/output.xml");

?>