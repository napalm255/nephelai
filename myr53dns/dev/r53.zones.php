<?php
  session_start();
  require_once('classes/security.class.php');
  $security = new Security();
  if (!$security->checkpoint()) { header('Location: /dev/index.php'); exit(); }

  include('header.php');
?>

  <script type="text/javascript" src="js/r53.js"></script>

  <div class="block">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Hosted Zones</h2>
      <ul class="tabs">
        <li><a href="#" id="hz_create" rel="facebox"><img src="images/new.png" alt="Create New Zone" /></a></li>
        <li><a href="#" id="hz_refresh"><img src="images/refresh.png" alt="Refresh" /></a></li>
      </ul>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    <div id="zones_msg"></div>
    <div id="hosted_zones"></div>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <script type="text/javascript">
    zonesGet_Zones();
    $('#hz_create').click(function() { zonesForm_Create(); return false; });
    $('#hz_refresh').click(function() { zonesGet_Zones(); return false; });
  </script>

<?php
  include('footer.php');
?>
