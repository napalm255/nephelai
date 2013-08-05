<?php
  session_start();
  require_once(dirname(__FILE__).'/version.php');
  require_once(dirname(__FILE__).'/classes/security.class.php');

  $security = new Security();
  if (!$security->checkpoint()) { header('Location: index.php'); exit(); }

?>
<?php include('header.php'); ?>

<!-- Page # Home -->
<div data-role="page" id="home" data-theme="a">
  <div data-role="header">
    <h1>Nephelai Mobile <br />.: Home :.</h1>
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-icon="grid">Account</a></li>
        <li><a href="index.php?logout" data-icon="delete">Logout</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <ul data-role="listview" data-inset="true" data-filter="false">
      <li><a href="m.r53.php" data-transition="slide">Route 53</a></li>
      <li><a href="m.ec2.php" data-transition="slide">EC2</a></li>
      <li><a href="#soon" data-transition="slide">Direct Connect</a></li>
      <li><a href="#soon" data-transition="slide">Elastic MapReduce</a></li>
      <li><a href="#soon" data-transition="slide">VPC</a></li>
      <li><a href="#soon" data-transition="slide">CloudFront</a></li>
      <li><a href="#soon" data-transition="slide">Glacier</a></li>
      <li><a href="#soon" data-transition="slide">S3</a></li>
      <li><a href="#soon" data-transition="slide">Storage Gateway</a></li>
      <li><a href="#soon" data-transition="slide">DynamoDB</a></li>
      <li><a href="#soon" data-transition="slide">ElastiCache</a></li>
      <li><a href="#soon" data-transition="slide">RDS</a></li>
      <li><a href="#soon" data-transition="slide">CloudFormation</a></li>
      <li><a href="#soon" data-transition="slide">CloudWatch</a></li>
      <li><a href="#soon" data-transition="slide">Elastic Beanstalk</a></li>
      <li><a href="#soon" data-transition="slide">IAM</a></li>
      <li><a href="#soon" data-transition="slide">CloudSearch</a></li>
      <li><a href="#soon" data-transition="slide">SES</a></li>
      <li><a href="#soon" data-transition="slide">SNS</a></li>
      <li><a href="#soon" data-transition="slide">SQS</a></li>
      <li><a href="#soon" data-transition="slide">SWF</a></li>
    </ul>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->
<?php $pageTitle=""; ?>

<!-- Page # Coming Soon -->
<div data-role="page" id="soon" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1>Nephelai Mobile</h1>
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
  Coming Soon!
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->

<?php include('footer.php'); ?>
