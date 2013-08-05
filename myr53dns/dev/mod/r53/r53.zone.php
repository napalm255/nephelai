<?php
  session_start();
  require_once(dirname(__FILE__).'/../../classes/security.class.php');
  $security = new Security();
  if (!$security->checkpoint()) { header('Location: /dev/index.php'); exit(); }

  require(dirname(__FILE__).'/../../header.php');
?>
  <script type="text/javascript" src="js/r53.js"></script>
  <div class="block">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2><?php echo($_GET['name']); ?>&nbsp;&nbsp;(<?php echo($_GET['id']); ?>)</h2>
      <ul class="tabs">
        <li><a href="#ns">Name Servers</a></li>
        <li><a href="#soa">State of Authority</a></li>
      </ul>
    </div>  <!-- .block_head ends -->
    <div class="block_content tab_content" id="ns">
    </div>  <!-- .block_content ends -->
    <div class="block_content tab_content" id="soa">
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <div class="block">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Resource Records</h2>
      <ul class="tabs">
        <li><a href="#" id="rr_create" rel="facebox"><img src="../../images/new.png" alt="Create New Record" /></a></li>
        <li><a href="#" id="rr_refresh"><img src="../../images/refresh.png" alt="Refresh" /></a></li>
      </ul>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    <div id="zone_msg"></div>
    <div id="records"></div>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <script type="text/javascript">
    zoneGet_NS($.query.get('id'));
    zoneGet_SOA($.query.get('id'),$.query.get('name'));
    zoneGet_Records($.query.get('id'),$.query.get('name'));
    $('#rr_create').click(function() { zoneForm_Create($.query.get('id'),$.query.get('name'),'{}'); return false; });
    $('#rr_refresh').click(function() { zoneGet_Records($.query.get('id'),$.query.get('name')); return false; });
  </script>

<?php
  require(dirname(__FILE__).'/../../footer.php');
?>
