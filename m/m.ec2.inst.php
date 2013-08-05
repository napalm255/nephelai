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

<!-- Page # EC2 # Instances -->
<div data-role="page" id="ec2" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1>Nephelai Mobile <br /> .: Instances :.</h1>
    <a href="m.ec2.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-icon="plus" data-transition="slide">Launch</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-theme="a" data-divider-theme="a" data-autodividers="true" data-inset="true" id="instances">
        <?php
          $res = $ec2->describe_instances();
            foreach ($res->body->reservationSet->item as $item) {
              $iitem = $item->instancesSet->item;
              $dict = new stdClass();
              $dict->Name = "";
              $dict->Instance = "";
              $dict->State = "";
              $dict->Type = "";
              $dict->LaunchTime = "";
              $dict->AMI = "";
              if (isset($iitem->tagSet->item->value)) { $dict->Name = fempty($iitem->tagSet->item->value); }
              if (isset($iitem->instanceId)) { $dict->Instance = fempty($iitem->instanceId); }
              if (isset($iitem->instanceState->name)) { $dict->State = fempty($iitem->instanceState->name); }
              if (isset($iitem->instanceType)) { $dict->Type = fempty($iitem->instanceType); }
              if (isset($iitem->launchTime)) { $dict->LaunchTime = fempty(date("D, d-M-Y @ g:i:s A", strtotime($iitem->launchTime))); }
              if (isset($iitem->imageId)) { $dict->AMI = fempty($iitem->imageId); }
        ?>
          <?php
            switch ($iitem->instanceState->name) {
              case 'running':
                $statusIcon = "check";
                break;
              case 'stopped':
                $statusIcon = "alert";
                break;
              case 'stopping':
                $statusIcon = "alert";
                break;
              case 'terminated':
                $statusIcon = "delete";
                break;
              default:
                $statusIcon = "info";
                break;
            }
          ?>
          <li data-icon="<?php echo($statusIcon); ?>"><a href="m.ec2.inst.view.php?id=<?php echo($dict->Instance); ?>" data-transition="slide"><?php recho($dict->Name, $dict->Instance); ?><span style="float:right;font-size:12px;text-align:right;"><?php echo($dict->Type); ?></span><p/><p><?php echo($dict->Instance . ", " . $dict->AMI); ?></p><p><?php echo($dict->LaunchTime); ?></p></a></li>
        <?php
            }
        ?>
      </ul>
      <script>
        //$('ul#instances>li').tsort();
      </script>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->

<?php include('footer.php'); ?>