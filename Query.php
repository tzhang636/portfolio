<html>

<head>
<link rel="stylesheet" type="text/css" href="./style/MyStyle.css" />
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
<script type="text/javascript" src="Query.js"></script>
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
<?php 
require_once("Connect.php");
require("Insert.php");

/**
 * Inserts into the database the form data.
 */
insertToDB($_POST["assignment"], $_POST["parent_id"], $_POST["comment"]);
?>

<br/>
<a href="javascript:void(0);" class="comments">Back to comments</a>
<br/>
<a href="javascript:void(0);" class="homepage">Back to homepage</a>
</div>
<!-- end of left column -->

<!-- right column -->
<div id="rh-col"><br />
</div>
<!-- end of right column -->

</body>
</html>