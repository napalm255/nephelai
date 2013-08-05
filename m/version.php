<?php
  header('Cache-Control: no-cache');
  header('Pragma: no-cache');

  ini_set('display_errors', 1);
  error_reporting(E_ALL|E_STRICT);

  $nephelai_mobile_version = "12.10-27(0)B";
  $JQUERY_MOBILE["PATH"] = "/m/jquery/mobile/1.2.0";
  $JQUERY_MOBILE["CSS"] = "/m/jquery/mobile/1.2.0";
  $JQUERY["PATH"] = "/m/jquery";

  require_once(dirname(__FILE__).'/m.f.php');
?>
