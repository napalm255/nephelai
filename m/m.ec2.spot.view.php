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

<!-- Page # EC2 # Spot Requests-->
<?php
  $res = $ec2->describe_spot_instance_requests($_GET['id']);
  $item = $res->body->spotInstanceRequestSet->item;
  $dict =  array(
  	'Request ID' => $item->spotInstanceRequestId,
  	'Instance' => $item->instanceId,
  	'Max Price' => "$" . $item->spotPrice,
  	'AMI' => $item->launchSpecification->imageId,
		'Type' => $item->launchSpecification->instanceType,
  	'State' => $item->state,
  	'Status' => $notyet,
  	'Monitoring Enabled' => $item->launchSpecification->monitoring->enabled,
  	'Created' => $item->createTime,
  	'Request Valid From' => $item->validFrom,
  	'Request Valid Until' => $item->validUntil,
  	'Key Pair Name' => $item->launchSpecification->keyName,
  	'Security Group(s)' => $item->launchSpecification->groupSet->item,
  	'Status Message' => $notyet,
  	'State Reason' => $notyet,
  	'Subnet' => $notyet,
  	'Kernel ID' => $notyet,
  	'Launched availability zone' => $item->launchedAvailabilityZone,
  	'Request Persistence' => $item->type,
  	'Availability Zone' => $item->launchSpecification->placement->availabilityZone,
  	'Availability Zone Group' => $item->availabilityZoneGroup,
  	'Launch Group' => $item->launchGroup,
  	'Status Update Time' => $notyet,
  	'Product Description' => $item->productDescription,
  	'RAM Disk ID' => $notyet,
  	'IAM Role' => $notyet
  );

?>
<div data-role="page" id="ec2" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1 id="title">Nephelai Mobile <br /> .: Spot Request :.</h1>
    <a href="m.ec2.spot.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="m.ec2.spot.act.php?id=<?php echo($dict["Instance"]); ?>&act=cancel" data-icon="delete" data-transition="slide" title="Cancel Spot Request" >Cancel Spot Request</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-inset="true" data-theme="a" data-content-theme="a">
        <li data-role="list-divider"><?php echo($dict["Request ID"]); ?><span style="float:right;"><?php echo($dict["Max Price"]); ?></span></li>
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