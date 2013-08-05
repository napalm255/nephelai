<?php
  $path = str_replace($_SERVER['DOCUMENT_ROOT'],'',dirname(__FILE__)).'/';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>Nephelai</title>
  <style type="text/css" media="all">
    @import url("<?php echo($path); ?>css/style.css");
    @import url("<?php echo($path); ?>css/jquery.wysiwyg.css");
    @import url("<?php echo($path); ?>css/facebox.css");
    @import url("<?php echo($path); ?>css/visualize.css");
    @import url("<?php echo($path); ?>css/date_input.css");
  </style>
  <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=7" /><![endif]-->
  <!--[if lt IE 8]><style type="text/css" media="all">@import url("css/ie.css");</style><![endif]-->
  <!--[if IE]><script type="text/javascript" src="js/excanvas.js"></script><![endif]-->
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.img.preload.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.filestyle.mini.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.wysiwyg.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.date_input.pack.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/facebox.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.visualize.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.select_skin.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.tablesorter.min.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/ajaxupload.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.pngfix.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.query-2.1.7.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.base64.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/jquery.ui.confirm.js"></script>
  <script type="text/javascript" src="<?php echo($path); ?>js/custom.js"></script>
</head>
<body>
  <div id="hld">
    <div class="wrapper">  <!-- wrapper begins -->
    <div id="header">
      <div class="hdrl"></div>
      <div class="hdrr"></div>
      <h1><a href="<?php echo($path); ?>index.php">Nephelai</a></h1>
      <ul id="nav">
        <li class="active"><a href="#">Route 53</a>
          <ul>
            <li><a href="<?php echo($path); ?>r53.zones.php">Hosted Zones</a></li>
          </ul>
        </li>
        <li><a href="<?php echo($path); ?>r53.faq.php">FAQ</a></li>
      </ul>
      <p class="user">Welcome | <a href="<?php echo($path); ?>index.php?logout">Logout</a></p>
    </div>  <!-- #header ends -->
