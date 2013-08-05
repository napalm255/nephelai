<?php
  session_start();
  require_once(dirname(__FILE__).'/classes/r53.class.php');
  require_once(dirname(__FILE__).'/../../classes/security.class.php');
  $security = new Security();
  if (!$security->checkpoint()) { exit(); }

  $r = new r53();
  $r->init();
  $r->setKey($_SESSION['awsKey']);
  $r->setSecret($_SESSION['awsSecret']);
?>

<?php if ($_POST['get_soa'] == 'true') { ?>
  <?php $zoneSOA = $r->getZoneSOA($_POST['zone_id'], $_POST['zone_name']); ?>
  <h3>State of Authority</h3>
  <div style="float:left;">
  <?php
    echo('Name: '.$zoneSOA->Name.'<br />');
    echo('Source: '.$zoneSOA->Source.'<br />');
    echo('Contact: '.$zoneSOA->Contact.'<br />');
    echo('Serial: '.$zoneSOA->Serial.'<br />');
    echo('TTL: '.$zoneSOA->TTL.'<br />');
  ?>
  </div>
  <div style="float:left;padding-left:40px;">
  <?php
    echo('Refresh: '.$zoneSOA->Refresh.'<br />');
    echo('Retry: '.$zoneSOA->Retry.'<br />');
    echo('Expire: '.$zoneSOA->Expire.'<br />');
    echo('Minimum TTL: '.$zoneSOA->MinimumTTL.'<br />');
  ?>
  </div>

<?php } else if ($_POST['get_ns'] == 'true') { ?>
  <h3>Name Servers</h3>
  <?php
    $zoneNS = $r->getZoneNS($_POST['zone_id']);
    for ($i = 0; $i < 4; $i++) {
      echo($zoneNS->DelegationSet->NameServers->NameServer[$i].'<br />');
    }
  ?>
  <br />

<?php } else if ($_POST['get_change'] == 'true') { ?>
  <h3>Last Change Status</h3>
  Status:&nbsp;<br />
  Submitted At:&nbsp;<br />
  ID:&nbsp;
  <br /><br /><br />

<?php } else if ($_POST['get_records'] == 'true') { ?>
  <table cellpadding="0" cellspacing="0" width="100%" class="sortable">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <th>Name</th>
        <th>Type</th>
        <th>TTL</th>
        <th>Value <span style="font-style:italic;float:right;">(Semicolon ; delimited)</span></th>
        <th>&nbsp;</th>
      </tr>
    </thead>
    <tbody>
  <?php
  $cnt=0;
  foreach ($r->getZoneRR($_POST['zone_id']) as $zone) {
    $zone->Id = $_POST['zone_id'];
    $zName = trim(preg_replace(array('/(\.)?'.$_POST['zone_name'].'/','/^$/','/\\\\052/'),array('','@','*'),$zone->Name));
    $aryValues = "";
    $idSave = "rr_save".$cnt; $idCopy = "rr_copy".$cnt; $idDelete = "rr_delete".$cnt;
    $idID = "rr_id".$cnt; $idName = "rr_name".$cnt; $idType = "rr_type".$cnt; $idTTL = "rr_ttl".$cnt; $idValue = "rr_value".$cnt;
    foreach ($zone->ResourceRecords->ResourceRecord as $resRec) { $aryValues .= $resRec->Value.";"; }
  ?>
        <tr>
          <td style="width:5%;"><a href="#" id="<?php echo($idDelete); ?>" title="Delete"><img src="../../images/x.png" alt="X" title="Delete Record" /></a></td>
          <td style="width:10%;" id="<?php echo($idName); ?>"><?php echo($zName); ?></a></td>
          <td style="width:10%;" id="<?php echo($idType); ?>"><?php echo($zone->Type); ?></td>
          <?php
            $i = 0; $rVal = '';
            foreach ($zone->ResourceRecords->ResourceRecord as $recValues) {
              $rVal .= $recValues->Value;
              if ($i < count($zone->ResourceRecords->ResourceRecord)-1) { $rVal .= ';';}
              $i++;
            }
            $zone->ResourceRecords = null;
            $zone->Value = base64_encode($rVal);
            $zone->Subdomain = preg_replace(array('/(\.)?'.$_POST['zone_name'].'$/','/^$/','/^\\\052$/'),array('','@','*'),$zone->Name);
            $zone->Name = $_POST['zone_name'];
          ?>
          <td style="width:10%;"><input type="text" style="width:100%;font-size:12px;" id="<?php echo($idTTL); ?>" value="<?php echo($zone->TTL); ?>" /></td>
          <td style=""><textarea style="width:100%;height:32px;font-size:12px;" id="<?php echo($idValue); ?>"><?php echo($rVal); ?></textarea></td>
          <td style="width:5%;"><a href="#" id="<?php echo($idSave); ?>" title="Save"><img src="../../images/save.png" alt="S" title="Save Record" /></a><a href="#" id="<?php echo($idCopy); ?>" title="Copy"><img src="../../images/copy.png" alt="C" title="Copy to New" /></a></td>
        </tr>
        <script>
          $("#<?php echo($idSave); ?>").click(function() { zoneSave_Record('<?php echo(json_encode($zone)); ?>','{"TTL":"'+$("#<?php echo($idTTL); ?>").val()+'",' + '"Value":"'+$.base64Encode($("#<?php echo($idValue); ?>").val())+'"}' ); return false; });
          $("#<?php echo($idCopy); ?>").click(function() { zoneForm_Create($.query.get('id'),$.query.get('name'),'<?php echo(json_encode($zone)); ?>'); return false; });
          $("#<?php echo($idDelete); ?>").click(function() { zoneDelete_Record('<?php echo(json_encode($zone)); ?>'); return false; });
        </script>
  <?php
    $cnt++;
  }
  ?>
    </tbody>
  </table>
  <script type="text/javascript">
    // Sort table
    $("table.sortable").tablesorter({
      headers: { 0: { sorter: false}, 5: { sorter: false} },
      widgets: ['zebra'],
      sortList: [[2,0],[1,0]]
    });
    $('.block table tr th.header').css('cursor', 'pointer');
  </script>

<?php } else if ($_POST['zone_form_create'] == 'true') { ?>
  <div class="block" id="form_create" style="width:500px;">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Create Resource Record</h2>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    <form id="rrc_form">
      <p>
      <label>Domain Name</label>
      <input class="text small" id="rrc_name" value="" type="text" />
      (Automatically appended: <b><?php echo($_POST['zone_name']); ?></b>)
      </p>
      <p>
      <label>Type</label>
      <select class="styled" id="rrc_type">
        <option value="A">A</option>
        <option value="CNAME">CNAME</option>
        <option value="AAAA">AAAA</option>
        <option value="MX">MX</option>
        <option value="NS">NS</option>
        <option value="PTR">PTR</option>
        <option value="SOA">SOA</option>
        <option value="SPF">SPF</option>
        <option value="SRV">SRV</option>
        <option value="TXT">TXT</option>
      </select>
      <span class="note" id="rrc_type_note"></span>
      </p>
      <p>
      <label>TTL</label><br />
      <input class="text small" id="rrc_ttl" value="3600" type="text" />
      </p>
      <p>
      <label>Value</label>
      <textarea class="text small" id="rrc_value" style="min-height:24px;height:32px;min-width:420px;max-width:440px;width:420px;"></textarea>
      </p>
      <p>
      <input class="submit long" type="button" id="rrc_submit" value="Create Record" />
      </p>
    </form>
    <span class="note">*If Domain Name and Type match an existing record, an update will be performed.</span>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <script>
    zoneCreate_Form_Type_Hints();
    $("form select.styled").select_skin();
    $('#rrc_type').change(function() { zoneCreate_Form_Type_Hints(); });
    $('#rrc_submit').click(function(){ zoneCreate_Record('{ "Id" : "<?php echo($_POST['zone_id']); ?>", "Subdomain" : "' + $('#rrc_name').val() + '", "Name" : "<?php echo($_POST['zone_name']); ?>", "Type" : "' + $('#rrc_type').val()  + '", "TTL" : "' + $('#rrc_ttl').val() + '", "Value" : "' + $.base64Encode($('#rrc_value').val()) + '" }'); $.facebox.close(); return false; });
  </script>

<?php } else if ($_POST['zone_save_record'] == 'true') { ?>
  <?php
    $action = "CREATE";
    $res = "";
    if ($r->recordExist($_POST['zone_name'],$_POST['zone_id'],$_POST['zone_type'])) { $action = "UPDATE";  }
    switch($action) {
      case 'CREATE':
        $res = $r->getResponse($r->createZoneRR($_POST['zone_name'],$_POST['zone_id'],$_POST['zone_type'],$_POST['zone_ttl'],base64_decode($_POST['zone_value'])));
        break;
      case 'UPDATE':
        $res = $r->getResponse($r->updateZoneRR($_POST['zone_name'],$_POST['zone_id'],$_POST['zone_type'],$_POST['zone_ttl'],base64_decode($_POST['zone_value'])));
        break;
    }
    echo(json_encode($res));
  ?>

<?php } else if ($_POST['zone_delete_record'] == 'true') { ?>
  <?php
    $res = $r->getResponse($r->deleteZoneRR($_POST['zone_name'],$_POST['zone_id'],$_POST['zone_type'],$_POST['zone_ttl'],base64_decode($_POST['zone_value'])));
    echo(json_encode($res));
  ?>

<?php } ?>

<?php
  $r->close();
?>
