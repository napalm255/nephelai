<?php
  require_once(dirname(__FILE__).'/classes/security.class.php');
  ini_set('display_errors', 1);
  error_reporting(E_ALL|E_STRICT);

  session_start();

  if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    session_write_close();
    header('Location: index.php'); exit();
  }

  if (isset($_SESSION['loggedIn'])) {
    if ($_SESSION['loggedIn']) { header('Location: mod/r53/r53.zones.php'); exit(); }
  }

  if (!empty($_POST['key']) && !empty($_POST['secret'])) {
    if (isset($_POST['key']) && isset($_POST['secret'])) {
      $_SESSION['loggedIn'] = true;
      $_SESSION['awsKey'] = $_POST['key'];
      $_SESSION['awsSecret'] = $_POST['secret'];
      session_write_close();
      header('Location: mod/r53/r53.zones.php'); exit();
    } else {
      $_SESSION['loggedIn'] = false;
      session_write_close();
    }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <title>MyR53DNS</title>
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
 
  <script type="text/javascript" src="js/jquery.js"></script>
  <script type="text/javascript" src="js/jquery.img.preload.js"></script>
  <script type="text/javascript" src="js/jquery.filestyle.mini.js"></script>
  <script type="text/javascript" src="js/jquery.wysiwyg.js"></script>
  <script type="text/javascript" src="js/jquery.date_input.pack.js"></script>
  <script type="text/javascript" src="js/facebox.js"></script>
  <script type="text/javascript" src="js/jquery.visualize.js"></script>
  <script type="text/javascript" src="js/jquery.select_skin.js"></script>
  <script type="text/javascript" src="js/jquery.tablesorter.min.js"></script>
  <script type="text/javascript" src="js/ajaxupload.js"></script>
  <script type="text/javascript" src="js/jquery.pngfix.js"></script>
  <script type="text/javascript" src="js/custom.js"></script>
  <script type="text/javascript" src="js/r53.js"></script>
</head>
<body>
  <div id="hld">
    <div class="wrapper">  <!-- wrapper begins -->
      <div class="block small center login">
   		  <div class="block_head">
          <div class="bheadl"></div>
     			<div class="bheadr"></div>
     			<h2>MyR53DNS</h2>
     			<ul>
     			  <li><a href="#" id="login_faq" rel="facebox">FAQ</a></li>
     			</ul>
        </div>  <!-- .block_head ends -->
        <div class="block_content">
          <?php
            if (isset($_SESSION['loggedIn'])) {
              if (!$_SESSION['loggedIn']) {
                echo('<div class="message errormsg"><p>Error Logging In.</p></div>');
                unset($_SESSION['loggedIn']); session_write_close();
              }
            }
          ?>
          <form action="index.php" method="post">
            <p>
              <label>Access Key ID:</label> <br />
              <input type="text" class="text" name="key" value="" />
            </p>
            <p>
              <label>Secret Access Key:</label> <br />
              <input type="password" class="text" name="secret" value="" />
            </p>
            <p style="float:left;width:25%;">
              <input type="submit" class="submit" value="Login" /> &nbsp; 
            </p>
          </form>
        </div>  <!-- .block_content ends -->
        <div class="bendl"></div>
        <div class="bendr"></div>
      </div>  <!-- .login ends -->
    </div>  <!-- wrapper ends -->
  </div>  <!-- #hld ends -->
  <script type="text/javascript">
    $('#login_faq').click(function() { faq(); return false; });
  </script>
</body>
</html>
