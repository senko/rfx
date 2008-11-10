<?php
    header('Content-Type: text/html; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8" ?>' . "\n"; 
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
<title><?=htmlspecialchars($content_title);?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
<meta name="robots" content="index,follow"></meta>
<meta name="distribution" content="global"></meta>
<meta name="description" content="Test Project"></meta>
<meta name="keywords" content="rfx, rei"></meta>
<meta name="author" content="REI"></meta>
<!-- Can be used to reset CSS, so it's easier to override across browsers
    <link href="static/css/reset.css" rel="stylesheet" type="text/css" />
-->
<link href="static/css/style.css" rel="stylesheet" type="text/css" />

</head>
<body>
<!-- HEAD -->
<div id="head">
    <h1 style="text-align: center; padding-bottom: 0.5em; border-bottom: 1px solid #aaa;
        color: #2244aa; margin-bottom: 1em;">RFX default page template</h1>
</div>
<!-- /HEAD -->

<!-- CONTENT -->
<? require('view/' . $content_page . '.php'); ?>
<!-- /CONTENT -->

<!-- FOOTER -->
<div style="text-align: center; font-size: 0.9em; padding-top: 0.5em; border-top: 1px solid #aaa; margin-top: 1em;">
    Copyright &copy; 2007-2008 REI
</div>
<!-- /FOOTER -->
</body>
</html>
