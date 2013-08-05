<?php
  session_start();
  require_once('classes/r53.class.php');
  # Security Checkpoint
  if ($_POST["token"] == md5(date("Y-m-d"))) {
    echo("Authorized\n");
    $p = json_decode(base64_decode($_POST["formula"]));
    if (is_null($p->val)) {
      $p->val = $_SERVER["REMOTE_ADDR"];
    }
  } else {
    echo("Unauthorized\n");
    exit();
  }

  $r = new r53();
  $r->init();
  $r->setKey($p->key);
  $r->setSecret($p->secret);

  $zone_host_records = base64_encode("$p->name,$p->type,$p->ttl,$p->val");

  $host_records = explode("\n",base64_decode($zone_host_records));
  foreach ($host_records as $host_record) {
    $host_record_opts = explode(',', $host_record);
    $rr_name = $host_record_opts[0] . '.' . $p->zone_name;
    $rr_type = strtoupper($host_record_opts[1]);
    $rr_ttl = $host_record_opts[2];
    $rr_value = $host_record_opts[3];
    $action = "CREATE";
    $res = "";
    if ($r->recordExist($rr_name,$p->zone_id,$rr_type)) { $action = "UPDATE"; }
    switch($action) {
      case 'CREATE':
        $res = $r->getResponse($r->createZoneRR($rr_name,$p->zone_id,$rr_type,$rr_ttl,$rr_value));
        break;
      case 'UPDATE':
        $res = $r->getResponse($r->updateZoneRR($rr_name,$p->zone_id,$rr_type,$rr_ttl,$rr_value));
        break;
    }
    echo(json_encode($res));
  }
?>
