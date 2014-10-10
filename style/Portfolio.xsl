<?xml version="1.0" encoding="ISO-8859-1"?>
<xsl:stylesheet version="1.0" 
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- 
Extracts the contents of the xml file and displays it inside
multiple layers of unordered lists. -->
<xsl:template match="/">
    <xsl:text disable-output-escaping='yes'>&lt;!DOCTYPE html></xsl:text> 
	<html>
	<head>
		<title>CS 242 Portfolio</title>
		<link rel="stylesheet" type="text/css" href="./style/MyStyle.css" />
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js"></script>
		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript" src="Portfolio.js"></script>
	</head>

	<body>	
	
	<!-- Header -->
	<div id="hdr">
		<br/>
		<img src="./style/logo.bmp" alt="Logo" width="80" height="80" />
		<h1>CS 242: Programming Studio</h1>
		<h2>University of Illinois at Urbana-Champaign</h2>
	</div>
	<!-- end of header -->
	
	<!-- left column -->
	<div id="lh-col">
   		<xsl:for-each select="SVNDump/project">
   			<div class="accordionButton"><a name="{title}"><xsl:value-of select="title"/></a></div>
   			<div class="accordionContent">		
			<ul id="a">									
				<b>Date: </b><xsl:value-of select="date"/><br/>
				<b>Version: </b><xsl:value-of select="version"/><br/>
				<b>Summary: </b><xsl:value-of select="summary"/><br/>
				<b>Project Files: </b><br/>
				<br/>
				<xsl:for-each select="files/file">
					<ul id="b">
						<li>			
						<b>File: </b><a href="https://subversion.ews.illinois.edu/svn/fa11-cs242{path}" class="svn" id="{path}"><xsl:value-of select="path"/></a><br/>
						<b>Size: </b><xsl:value-of select="size"/><br/>
						<b>Doctype: </b><xsl:value-of select="doctype"/><br/>
						<b>File Versions: </b><br/>
						<br/>	
						<xsl:for-each select="versions/version">
							<ul id="c">
								<li>
								<b>Version: </b><xsl:value-of select="number"/><br/>
								<b>Author: </b><xsl:value-of select="author"/><br/>
								<b>Info: </b><xsl:value-of select="info"/><br/>
								<b>Date: </b><xsl:value-of select="date"/><br/>
								<br/>
								</li>
							</ul>
						</xsl:for-each>	
						</li>
						<script> 
							addToArray("<xsl:value-of select="path"/>", <xsl:value-of select="size"/>);
						</script>
					</ul>					
				</xsl:for-each>
				
				<div id="{title}"></div> <!-- Div that will hold the bar chart -->
				
				<script> 
					drawGraph("<xsl:value-of select="title"/>");
				</script>
				
				<a href="javascript:void(0);" class="comments">Leave a comment</a>
				
			</ul>
			</div>
		</xsl:for-each>
	</div> <!-- end of left column -->
	
	<!-- right column -->
	<div id="rh-col"><br />
		<h3>Project Links</h3>
		<xsl:for-each select="SVNDump/project">
			<ul id="a">
			<li>
			<b><a href="#{title}"><xsl:value-of select="title"/></a></b>
			<br/>
			</li>
			</ul>
		</xsl:for-each>
	</div>
	<!-- end of right column -->
	
	</body>
	</html>
</xsl:template>
</xsl:stylesheet>