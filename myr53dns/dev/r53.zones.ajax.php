<?php
  session_start();
  require_once('classes/security.class.php');
  $security = new Security();
  if (!$security->checkpoint()) { exit(); }

  require_once('classes/r53.class.php');
  $r = new r53();
  $r->init();
  $r->setKey($_SESSION['awsKey']);
  $r->setSecret($_SESSION['awsSecret']);
?>

<?php if ($_POST['get_zones'] == 'true') { ?>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th>ID</th>
        <th>Name</th>
        <th>Caller Reference</th>
      </tr>
    </thead>
    <tbody>
    <?php
      $zones = $r->getZones(); $cnt = 0;
      if (isset($zones)) { foreach ($zones as $zone) {
      echo('<tr>');
      echo('<td><a id="hz_delete'.$cnt.'" href="#" title="Delete"><img src="images/x.png" alt="X" /></a></td>');
      echo('<td>'.str_replace('/hostedzone/','',$zone->Id).'</td>');
      echo('<td><a href="r53.zone.php?name='.$zone->Name.'&id='.str_replace('/hostedzone/','',$zone->Id).'">'.$zone->Name.'</a></td>');
      echo('<td>'.$zone->CallerReference.'</td>');
      echo('</tr>');
      echo('<script type="text/javascript">$("#hz_delete'.$cnt.'").click(function() { hz_confirmDelete("'.str_replace('/hostedzone/','',$zone->Id).'"); });</script>');
      $cnt++;
      }}
    ?>
    </tbody>
  </table>
  <script type="text/javascript">
    // Sort table
    $("table.sortable").tablesorter({
      headers: { 0: { sorter: false} },
      widgets: ['zebra'],
      sortList: [[2,0]]
    });
    $('.block table tr th.header').css('cursor', 'pointer');

    function hz_confirmDelete($zone_id) {
      zonesDelete_Zone($zone_id);
    }
  </script>

<?php } else if ($_POST['zones_form_create'] == 'true') { ?>
  <div class="block" id="form_create" style="width:500px;">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Create Zone</h2>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    <form id="zone_form">
      <p>
      <label>Domain Name</label>
      <input class="text small" id="domain_name" value="" type="text" />
      </p>
      <p>
      <input class="submit long" type="button" id="zone_submit" value="Create Zone" />
      </p>
    </form>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <script>
    $('#zone_submit').click(function(){ zonesCreate_Zone('{ "Name" : "' + $('#domain_name').val() + '" }'); $.facebox.close(); return false; });
  </script>

<?php } else if ($_POST['zones_delete_zone'] == 'true') { ?>
  <?php
  $res = $r->getResponse($r->deleteZone($_POST['zone_id']));
  echo(json_encode($res));
  ?>

<?php } else if ($_POST['zones_create_zone'] == 'true') { ?>
  <?php
  $res = $r->getResponse($r->createZone($_POST['zone_name']));
  echo(json_encode($res));
  ?>

<?php } ?>

<?php
  $r->close();
?>
