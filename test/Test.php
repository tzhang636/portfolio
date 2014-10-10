<?php

require_once("../../../eclipse/simpletest/autorun.php");
require_once("../Parser.php");

class test extends UnitTestCase
{
	function testProjectArraySize() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml", 
						"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue(sizeof($parser->projects) == 1);
	}
	
	function testProjectTitle() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->title == "Assignment0");
	}
	
	function testProjectDate() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->date == "2011");
	}
	
	function testProjectVersion() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->version == 1.1);
	}
	
	function testProjectSummary() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->summary == "initial commit");
	}
	
	function testFileArraySize() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue(sizeof($parser->projects["Assignment0"]->files) == 1);
	}
	
	function testFileSize() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->size == 333);
	}
	
	function testFileDoctype() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->doctype == "code");
	}
	
	function testFilePath() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->path == "/zhang156/Assignment0/file.php");
	}
	
	function testVersionArraySize() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue(sizeof($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions) == 2);
	}
	
	function testVersionNumber() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["0"]->number == 0);
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["1"]->number == 1);
	}
	
	function testVersionAuthor() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["0"]->author == "zhang156");
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["1"]->author == "zhang156");
	}
	
	function testVersionInfo() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["0"]->info == "initial commit");
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["1"]->info == "changes to file.php");
	}
	
	function testVersionDate() {
		$parser = new Parser();
		$parser->parse("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/listInput.xml",
								"C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testInput/logInput.xml");
		$parser->output("C:/Users/Tom Zhang/Desktop/eclipse/workspace/Assignment3.1/test/testOutput/testOutput.xml");
		
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["0"]->date == "2011");
		$this->assertTrue($parser->projects["Assignment0"]->files["/zhang156/Assignment0/file.php"]->versions["1"]->date == "2012");
	}	
	
}



?>