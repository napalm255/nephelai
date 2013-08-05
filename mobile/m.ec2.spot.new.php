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
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
    <h1 id="title">Nephelai Mobile <br /> .: Spot Request :.</h1>
    <a href="m.ec2.spot.php" data-icon="back" data-iconpos="notext" data-direction="reverse">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="m.ec2.spot.php" data-icon="back" data-iconpos="notext" data-direction="reverse">Cancel</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <div data-role="collapsible-set">    
      	<div data-role="collapsible" data-collapsed="false">
      	<h3>Choose an AMI</h3>
      	<?php 
      	  /*
      	   * Quick Start
      	   * My AMIs
      	   * Community AMIs
      	   * AWS Marketplace
      	   * Manual Entry
      	   * 
      	   * - AMI ID, Root Device, Name, Platform
      	   */
      	?>
      		<div data-role="fieldcontain" style="text-align:center;">
						<select name="select-ami-self" id="select-ami-self">
          	<?php
          	  $ami = $ec2->describe_images(array('Owner' => 'self'));
          	  if ($ami->isOK()) {
          	    foreach ($ami->body->imagesSet->item as $item) {
          	      echo('<option value="">' . $item->name . '&nbsp;(' . $item->imageId . ')' . '</option>');
          	    }
          	  } else {
          	    echo("Error Reading AMIs");
          	  }      	
          	?>
          	</select>
          </div>
      	</div>
      	<div data-role="collapsible">
      	<h3>Instance Details</h3>
      	<?php 
      	  /*
      	   * Number of Instances
      	   * Instance Type
      	   * Launch as an EBS-Optimized Instance (additional charges apply) [if supported]
      	   * Current Price (Read-Only)
      	   * Max Price
      	   * Launch Group
      	   * Launch Into
      	   * 		-EC2
      	   * 				Availability Zone
      	   * 				Availability Zone Group
      	   * 		-VPC
      	   * 				Subnet
      	   * Request Valid From
      	   * Request Valid Until
      	   * Persistent Request
      	   * 
      	   */
      	?>
      	</div>
      	<div data-role="collapsible">
      	<h3>Advanced Instance Details</h3>
      	<?php 
      	  /*
      	   * Kernel ID
      	   * RAM Disk ID
      	   * Monitoring (Enable CloudWatch detailed monitoring for this instance) [additional charges will apply]
      	   * User Data [as text] [as file]
      	   * 		-base64 encoded
      	   * IAM Role
      	   * 
      	   */
      	?>
      	</div>
      	<div data-role="collapsible">
      	<h3>Storage</h3>
      	<?php 
      	  /*
      	   * Type, Device, Snapshot ID, Size, Volume Type IOPS, Delete on Termination
      	   * 
      	   */
      	?>
      	</div>
      	<div data-role="collapsible">
      	<h3>Key Pair</h3>
      	<?php 
      	  /*
      	   * Choose from your existing Key Pairs
      	   * Proceed without a Key Pair
      	   * [N] Create a new Key Pair
      	   * 
      	   */
      	?>      		      		
      		<div data-role="fieldcontain" style="text-align:center;">
						<select name="select-key-pair" id="select-key-pair">
          	<?php
          	  $kp = $ec2->describe_key_pairs();
          	  if ($kp->isOK()) {
          	    foreach ($kp->body->keySet->item as $item) {
          	      echo('<option value="">' . $item->keyName . '</option>');
          	    }
          	  } else {
          	    echo("Error Reading Key Pairs");
          	  }
          	?>
          	</select>
          </div>
      	</div>
      	<div data-role="collapsible">
      	<h3>Security Group</h3>
      	<?php 
      	  /*
      	   * Choose one or more of your existing Security Groups
      	   * [N] Create a new Security Group
      	   * 
      	   */
      	?>
      		<div data-role="fieldcontain" style="text-align:center;">
						<select name="select-security-group" id="select-security-group">
          	<?php
          	  $sg = $ec2->describe_security_groups();
          	  if ($sg->isOK()) {
          	    foreach ($sg->body->securityGroupInfo->item as $item) {
          	      echo('<option value="">' . $item->groupName . '</option>');
          	    }
          	  } else {
          	    echo("Error Reading Security Groups");
          	  }
          	?>
          	</select>
          </div>
      	</div>
      </div>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->
<?php include('footer.php'); ?>
