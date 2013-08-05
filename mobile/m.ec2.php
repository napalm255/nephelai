<?php
  session_start();
  require_once(dirname(__FILE__).'/version.php');
  require_once(dirname(__FILE__).'/classes/security.class.php');

  $security = new Security();
  if (!$security->checkpoint()) { header('Location: index.php'); exit(); }
?>
<?php include('header.php'); ?>

<!-- Page # EC2 -->
<div data-role="page" id="ec2" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1>Nephelai Mobile <br /> .: EC2 :.</h1>
    <a href="m.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-inset="true" data-filter="false">
        <li><a href="m.ec2.inst.php" data-transition="slide">Instances</a></li>
        <li><a href="m.ec2.spot.php" data-transition="slide">Spot Requests</a></li>
        <li><a href="m.ec2.resv.php" data-transition="slide">Reserved Instances</a></li>
        <li><a href="m.ec2.ami.php" data-transition="slide">AMIs</a></li>
        <li><a href="m.ec2.sec.php" data-transition="slide">Security Groups</a></li>
        <li><a href="m.ec2.eip.php" data-transition="slide">Elastic IPs</a></li>
        <li><a href="m.ebs.vol.php" data-transition="slide">EBS Volumes</a></li>
        <li><a href="m.ebs.snap.php" data-transition="slide">EBS Snapshots</a></li>
      </ul>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->

<?php include('footer.php'); ?>
