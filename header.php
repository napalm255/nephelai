<?php
  require_once('version.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Nephelai</title>
  <style type="text/css" media="all">
    @import url("css/style.css");
    @import url("css/jquery.wysiwyg.css");
    @import url("css/facebox.css");
    @import url("css/visualize.css");
    @import url("css/date_input.css");
  </style>
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
  <!--[if lt IE 8]><style type="text/css" media="all">@import url("css/ie.css");</style><![endif]-->
  <!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.img.preload.js"></script>
  <script type="text/javascript" src="js/jquery.filestyle.mini.js"></script>
  <script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
  <script type="text/javascript" src="js/jquery.date_input.pack.js"></script>
  <script type="text/javascript" src="js/jquery.facebox.js"></script>
  <script type="text/javascript" src="js/jquery.visualize.js"></script>
  <script type="text/javascript" src="js/jquery.select_skin.js"></script>
  <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
  <script type="text/javascript" src="js/jquery.pngfix.js"></script>
  <script type="text/javascript" src="js/jquery.query-2.1.7.js"></script>
  <script type="text/javascript" src="js/jquery.base64.js"></script>
  <script type="text/javascript" src="js/jquery.zrssfeed.min.js"></script>
  <script type="text/javascript" src="js/ajaxupload.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
</head>
<body>
  <div id="hld">
    <div class="wrapper">  <!-- wrapper begins -->
    <div id="header">
      <div class="hdrl"></div>
      <div class="hdrr"></div>
      <h1><a href="index.php">Nephelai</a></h1>
      <ul id="nav">
        <li class="active"><a href="r53.dashboard.php">Route 53</a>
          <ul>
            <li><a href="r53.dashboard.php">Dashboard</a></li>
            <li><a href="r53.zones.php">Hosted Zones</a></li>
            <li><a href="myr53dns/?zones">MyR53DNS (Legacy)</a></li>
          </ul>
        </li>
        <li><a href="#">Help</a>
          <ul>
            <li><a href="http://code.google.com/p/nephelai/wiki/HelpRoute53" target="new">Route 53</a></li>
          </ul>
        </li>
      </ul>
      <p class="user">Welcome | <a href="index.php?logout">Logout</a></p>
    </div>  <!-- #header ends -->
