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
<div data-role="page" id="ec2" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1 id="title">Nephelai Mobile <br /> .: Spot Requests :.</h1>
    <a href="m.ec2.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="m.ec2.spot.new.php" data-icon="plus" data-transition="slide">Request Spot Instance</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-theme="a" data-divider-theme="a" data-autodividers="true" data-inset="true" id="instances">
        <?php
          $res = $ec2->describe_spot_instance_requests();
            foreach ($res->body->spotInstanceRequestSet->item as $item) {              
              $dict = new stdClass();
              $dict->RequestId = "";
              $dict->Instance = "&nbsp;-&nbsp;";
              $dict->MaxPrice = "";
              $dict->State = "";
              $dict->Type = "";
              $dict->CreateTime = "";
              $dict->AMI = "";
              $dict->AvailabilityZone = "";
              $dict->ProductDescription = "";
              $dict->SpotPrice = "";
              $dict->RequestType = "";
              if (isset($item->spotInstanceRequestId)) { $dict->RequestId = fempty($item->spotInstanceRequestId); }
              if (isset($item->spotPrice)) { $dict->MaxPrice = fempty($item->spotPrice); }
              if (isset($item->state)) { $dict->State = fempty($item->state); }
              if (isset($item->launchSpecification->instanceType)) { $dict->Type = fempty($item->launchSpecification->instanceType); }
              if (isset($item->createTime)) { $dict->CreateTime = fempty(date("D, d-M-Y @ g:i:s A", strtotime($item->createTime))); }
              if (isset($item->launchSpecification->imageId)) { $dict->AMI = fempty($item->launchSpecification->imageId); }
              if (isset($item->instanceId)) { $dict->Instance = fempty($item->instanceId); }
              if (isset($item->launchSpecification->placement->availabilityZone)) { $dict->AvailabilityZone = fempty($item->launchSpecification->placement->availabilityZone); }
              if (isset($item->productDescription)) { $dict->ProductDescription = fempty($item->productDescription); }
              if (isset($item->type)) { $dict->RequestType = fempty($item->type); }
              
              $spr = $ec2->describe_spot_price_history(array(
                                    'InstanceType' => array($dict->Type),
                                    'ProductDescription' => $dict->ProductDescription,
                                    'AvailabilityZone' => $dict->AvailabilityZone,
                                    'MaxResults' => 10
              ));
              
              $spot = $spr->body->spotPriceHistorySet->item;
              if (isset($spot->spotPrice)) { $dict->SpotPrice = fempty($spot->spotPrice); }
        ?>
          <?php
            switch ($dict->State) {
              case 'active':
                $statusIcon = "check";
                break;
              case 'canceled':
                $statusIcon = "delete";
                break;
              default:
                $statusIcon = "info";
                break;
            }
          ?>
          <li data-icon="<?php echo($statusIcon); ?>"><a href="m.ec2.spot.view.php?id=<?php echo($dict->RequestId); ?>" data-transition="slide"><?php echo($dict->RequestId); ?><span style="float:right;font-size:12px;text-align:right;margin:10px;"><p style="font-size:12px;font-weight:bold;"><?php echo($dict->Type); ?></p><p>$<?php echo((float)$dict->MaxPrice); ?><span style="font-size:10px;">&nbsp;Max</span></p><p style="font-style:italic;">$<?php echo((float)$dict->SpotPrice); ?><span style="font-size:10px;">&nbsp;Now</span></p></span><p/><p><?php echo(ucfirst($dict->RequestType)); ?></p><p><?php echo($dict->Instance . ", " . $dict->AMI); ?></p><p><?php echo($dict->CreateTime); ?></p></a></li>
        <?php
            }
        ?>
      </ul>
      <script>
        $('ul#instances>li').tsort();
      </script>
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
