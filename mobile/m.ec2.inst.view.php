<?php
  session_start();
  require_once(dirname(__FILE__).'/version.php');
  require_once(dirname(__FILE__).'/classes/security.class.php');
  require_once('sdk/sdk.class.php');

  $security = new Security();
  if (!$security->checkpoint()) { header('Location: index.php'); exit(); }

  $ec2 = new AmazonEC2();
?>
<?php include('header.php'); ?>

<!-- Page # EC2 # Instances # View -->
<?php
  $res = $ec2->describe_instances(array('InstanceId' => $_GET['id']));
  $item = $res->body->reservationSet->item;
  $iitem = $item->instancesSet->item;
  $dict =  array(
             'Name' => $iitem->tagSet->item->value,
             'Instance' => $iitem->instanceId,
             'AMI ID' => $iitem->imageId,
             'Root Device Type' => $iitem->rootDeviceType,
             'Type' => $iitem->instanceType,
             'State' => $iitem->instanceState->name,
             'Elastic IP' => $iitem->ipAddress,
             'Public DNS' => $iitem->dnsName,
             'Private DNS' => $iitem->privateDnsName,
             'Private IPs' => $iitem->privateIpAddress,
             'Status Checks' => $notyet,
             'Alarm Status' => $notyet,
             'Monitoring' => $notyet,
             'Security Groups' => $iitem->groupSet->item->groupName,
             'Key Pair Name' => $iitem->keyName,
             'Virtualization' => $iitem->virtualizationType,
             'Placement Group' => $notyet,
             'Scheduled Events' => $notyet,
             'VPC ID' => $notyet,
             'Source/Dest Check' => $notyet,
             'RAM Disk ID' => $notyet,
             'IAM Role' => $notyet,
             'EBS Optimized' => $iitem->ebsOptimized,
             'Block Devices' => $notyet,
             'Launch Time' => $iitem->launchTime,
             'State Transition Reason' => $notyet,
             'Termination Protection' => $notyet,
             'Alarm Status' => $notyet,
             'Owner' => $iitem->ownerId,
             'Subnet ID' => $notyet,
             'Reservation' => $iitem->reservationId,
             'Platform' => $iitem->platform,
             'Kernel ID' => $iitem->kernelId,
             'AMI Launch Index' => $iitem->amiLaunchIndex,
             'Root Device' => $iitem->rootDeviceName,
             'Tenancy' => $iitem->placement->tenancy,
             'Lifecycle' => $notyet
           );
?>

<div data-role="page" id="ec2" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1 id="title">Nephelai Mobile <br /> .: Instance :.</h1>
    <a href="m.ec2.inst.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
      <?php if ($dict["State"] == "running") { ?>
        <li><a href="m.ec2.inst.act.php?id=<?php echo($dict["Instance"]); ?>&act=<?php echo(ssText($dict["State"])); ?>" data-icon="<?php echo(ssIcon($dict["State"])); ?>" data-transition="slide" title="Stop"><?php echo(ucfirst(ssText($dict["State"]))); ?></a></li>
        <li><a href="m.ec2.inst.act.php?id=<?php echo($dict["Instance"]); ?>&act=reboot" data-icon="gear" data-transition="slide" title="Reboot">Reboot</a></li>
        <li><a href="m.ec2.inst.act.php?id=<?php echo($dict["Instance"]); ?>&act=terminate" data-icon="delete" data-transition="slide" title="Terminate">Terminate</a></li>
      <?php } elseif ($dict["State"] == "stopped") { ?>
      	<li><a href="m.ec2.inst.act.php?id=<?php echo($dict["Instance"]); ?>&act=terminate" data-icon="delete" data-transition="slide" title="Terminate">Terminate</a></li>
      <?php } elseif ($dict["State"] == "terminated") { ?>
      	<li><a><?php echo(strtoupper($dict["State"])); ?></a></li>
      <?php } else { ?>
      <?php } ?>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-inset="true" data-theme="a" data-content-theme="a">
        <li data-role="list-divider"><?php recho($dict["Name"], $dict["Instance"]); ?></li>
        <li>
        <?php
          foreach ($dict as $k => $v) {
        ?>
          <div class="ui-grid-a">
            <div class="ui-block-a" style="border-bottom:#000000 1px solid;height:38px;"><div class="ui-bar" style="font-size:12px;"><?php fecho($k); ?></div></div>
            <div class="ui-block-b" style="border-bottom:#000000 1px solid;height:38px;"><div class="ui-bar" style="font-size:12px;"><?php fecho($v); ?></div></div>
          </div>
        <?php
          }
        ?>
        </li>
      </ul>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->
<script>
 $('#title').css( 'cursor', 'pointer' ).attr("Title", "Refresh").click(function(){location.reload();});
</script>
<?php include('footer.php'); ?>
