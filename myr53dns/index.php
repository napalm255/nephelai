<?php
// DEBUG
  #ini_set('display_errors', 1);
  #error_reporting(E_ALL|E_STRICT);

  include("r53.class.php");
  session_start();
  $version = "Version: 11.02-03(1)B";

  function securityCheckpoint() {
    if (!isset($_SESSION['loggedIn'])) {
      header('Location: ?'); exit();
    }
  }

  if (isset($_SESSION['loggedIn'])) {
    $r = new r53();
    $r->init();
    $r->setKey($_SESSION['awsKey']);
    $r->setSecret($_SESSION['awsSecret']);
  }

  if (isset($_GET['signout']) & isset($_SESSION['loggedIn'])) {
    session_unset();
    session_destroy();
    header('Location: ?'); exit();
  }
  if (isset($_GET['signin']) & !isset($_SESSION['loggedIn'])) {
    $_SESSION['loggedIn'] = 1;
    $_SESSION['awsKey'] = $_POST['key'];
    $_SESSION['awsSecret'] = $_POST['secret'];
    session_write_close();
    header('Location: ?zones'); exit();
  }
  if (isset($_GET['zones']) & isset($_GET['add'])) {
    securityCheckpoint();
    $r->createZone($_POST['zname']);
    header('Location: ?zones'); exit();
  }
  if (isset($_GET['zones']) & isset($_GET['delete'])) {
    securityCheckpoint();
    $r->deleteZone($_GET['id']);
    header('Location: ?zones'); exit();
  }
  if (isset($_GET['zone']) & isset($_GET['delete'])) {
    securityCheckpoint();
    $res = $r->getResponse($r->deleteZoneRR($_GET['name'],$_GET['id'],$_GET['type'],$_GET['ttl'],$_GET['value']));
    storeResponse($res);
    header('Location: ?zone&name='.$_SESSION['zoneName'].'&id='.$_SESSION['zoneId']); exit();
  }
  if (isset($_GET['zone']) & isset($_GET['add'])) {
    securityCheckpoint();
    $action = "CREATE";
    $name = $_POST['rname'];
    if (empty($_POST['rname'])) {
      $name = $_SESSION['zoneName'];
    } else {
      $name = $_POST['rname'].".".$_SESSION['zoneName'];
    }
    if ($r->recordExist($name,$_GET['id'],$_POST['rtype'])) { $action = "UPDATE";  }
    switch($action) {
      case 'CREATE':
        $res = $r->getResponse($r->createZoneRR($name,$_SESSION['zoneId'],$_POST['rtype'],$_POST['rttl'],$_POST['rvalue']));
        storeResponse($res);
        break;
      case 'UPDATE':
        $res = $r->getResponse($r->updateZoneRR($name,$_SESSION['zoneId'],$_POST['rtype'],$_POST['rttl'],$_POST['rvalue']));
        storeResponse($res);
        break;
    } 
    header('Location: ?zone&name='.$_SESSION['zoneName'].'&id='.$_SESSION['zoneId']); exit();
  }

  function storeResponse($xml) {
    if (isset($xml)) {
      switch($xml['Name']) {
        case 'Error':
          $_SESSION['errors'][$_SESSION['zoneName']]['Type'] = trim($xml['Type']);
          $_SESSION['errors'][$_SESSION['zoneName']]['Code'] = trim($xml['Code']);
          $_SESSION['errors'][$_SESSION['zoneName']]['Message'] = trim($xml['Message']);
          $_SESSION['errors'][$_SESSION['zoneName']]['Clear'] = 0;
          session_write_close();
          break;
        case 'Change':
          $_SESSION['changes'][$_SESSION['zoneName']]['Id'] = trim($xml['Id']);
          $_SESSION['changes'][$_SESSION['zoneName']]['Status'] = trim($xml['Status']);
          $_SESSION['changes'][$_SESSION['zoneName']]['Timestamp'] = trim($xml['Timestamp']);
          unset($_SESSION['errors'][$_SESSION['zoneName']]);
          session_write_close();
          break;
      }
    }
  }

  if (isset($_SESSION['zoneName']) & isset($_SESSION['changes'])) {
    if (isset($_SESSION['changes'][$_SESSION['zoneName']])) {
      if ($_SESSION['changes'][$_SESSION['zoneName']]['Status'] == "PENDING") {
        securityCheckpoint();
        $changeResults = $r->queryZoneChange($_SESSION['changes'][$_SESSION['zoneName']]['Id']);
        if ($changeResults->ChangeInfo->Status != "PENDING") {
          $_SESSION['changes'][$_SESSION['zoneName']]['Id'] = trim(str_replace('/change/','',$changeResults->ChangeInfo->Id));
          $_SESSION['changes'][$_SESSION['zoneName']]['Status'] = trim($changeResults->ChangeInfo->Status);
          $_SESSION['changes'][$_SESSION['zoneName']]['Timestamp'] = trim($changeResults->ChangeInfo->SubmittedAt);
          session_write_close();
        }
      }
    }
  }
  if (isset($_SESSION['zoneName']) & isset($_SESSION['errors'])) {
    if (isset($_SESSION['errors'][$_SESSION['zoneName']])) {
      if ($_SESSION['errors'][$_SESSION['zoneName']]['Clear'] == 1) {
        unset($_SESSION['errors'][$_SESSION['zoneName']]);
        session_write_close();
      } elseif ($_SESSION['errors'][$_SESSION['zoneName']]['Clear'] == 0) {
        $_SESSION['errors'][$_SESSION['zoneName']]['Clear'] = 1;
        session_write_close();
      }
    }
  }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link rel="stylesheet" href="css/HigherGround.css" type="text/css" />
<title>MyR53DNS</title>
<script src="mootools-core-1.3-full-nocompat-yc.js" type="text/javascript"></script>
<script src="mootools-more.js" type="text/javascript"></script>
<script src="myr53dns.js" type="text/javascript"></script>
</head>
<body>
<!-- wrap starts here -->
<div id="wrap">
  <div id="top-bg"></div>
			
  <!--header -->
  <div id="header">
    <h1 id="logo-text"><a href="?" title="">My<span>R53DNS</span></a></h1>		
    <p id="slogan">laying paving for route 53</p>
    <div id="header-links">
      <p>
        <?php echo("$version"); ?>
      </p>
    </div>		
  <!--header ends-->					
  </div>
		
  <!-- navigation starts-->	
  <div  id="nav">
    <ul>
    <?php
      ((strlen($_SERVER['QUERY_STRING']) == "0") ? $current="id=\"current\"" : $current="");
      echo("<li $current><a href=\"?\">Home</a></li>");
      if (isset($_SESSION['loggedIn'])) {
        ((isset($_GET['zones'])|isset($_GET['zone'])) ? $current="id=\"current\"" : $current="");
        echo("<li $current><a href=\"?zones\">DNS Zones</a></li>");
      }
      ((isset($_GET['faq'])) ? $current="id=\"current\"" : $current="");
      echo("<li $current><a href=\"?faq\">F.A.Q.</a></li>");
    ?>
    </ul>
  <!-- navigation ends-->	
  </div>					
			
  <!-- content-wrap starts -->
  <div id="content-wrap">
    <div id="main">
      <?php if (isset($_GET["zones"])) { ?>
      <?php securityCheckpoint(); ?>
      <?php unset($_SESSION['zoneName']); unset($_SESSION['zoneId']); session_write_close();?>
      <h2><a href="?zones" title="">Hosted DNS Zones</a></h2>
      <table style="width:750px;">
        <tr>
          <th></th>
	  <th>Id</th>
          <th>Name</th>
          <th>Caller Reference</th>
        </tr>
      <?php $zones = $r->getZones(); if (isset($zones)) { ?>
      <?php $cnt=0; foreach ($zones as $zone) { $cls = ($cnt % 2) ? 'row-b' : 'row-a'; ?>
        <tr class="<?php echo($cls); ?>">
          <td><a href="<?php echo("?zones&id=".str_replace('/hostedzone/','',$zone->Id)."&delete"); ?>" title"Delete"><img src="images/delete.png" alt="X" border="0" style="clear:both;width:16px;height:16px;padding:0px;border:0px;"></a></td>
          <td><?php echo(str_replace('/hostedzone/','',$zone->Id)); ?></td>
          <td><a href="<?php echo("?zone&name=".$zone->Name."&id=".str_replace('/hostedzone/','',$zone->Id)); ?>"><?php echo($zone->Name); ?></a></td>
          <td><?php echo($zone->CallerReference); ?></td>
        </tr>
      <?php $cnt++; } } ?>
      </table>

      <h3>Create Zone</h3>
      <form action="?zones&add" method="post" name="formZone">
        <p>
          <label>Domain Name</label>
          <input name="zname" value="" type="text" size="30" />
          &nbsp;&nbsp;&nbsp;&nbsp;
          <input class="button" type="submit" value="Create Zone" />
        </p>		
      </form>				
      <br />	

      <?php } elseif (isset($_GET["zone"])) { ?>
      <?php securityCheckpoint(); ?>
      <?php $_SESSION['zoneName'] = trim($_GET['name']); $_SESSION['zoneId'] = trim($_GET['id']); session_write_close();?>
      <h2><a href="?zones" title=""><?php echo($_GET['name']); ?><div style="font-size:14px;">(<?php echo($_GET['id']); ?>)</div></a></h2>
      <?php if (!empty($_SESSION['changes'][$_SESSION['zoneName']]['Status'])) { ?>
        <div style="padding-left:10px;">Status:&nbsp;<?php echo(trim($_SESSION['changes'][$_GET['name']]['Status'])); ?><br />Submitted At:&nbsp;<?php echo(trim($_SESSION['changes'][$_GET['name']]['Timestamp'])); ?><br />ID:&nbsp;<?php echo(trim($_SESSION['changes'][$_GET['name']]['Id'])); ?></div>
      <?php } ?>
      <?php if (!empty($_SESSION['errors'][$_SESSION['zoneName']])) { ?>
      <blockquote style="color:ff0000;"><?php echo("*ERROR*: ".trim($_SESSION['errors'][$_SESSION['zoneName']]['Type'])." -> ".trim($_SESSION['errors'][$_SESSION['zoneName']]['Code'])."<br />".trim($_SESSION['errors'][$_SESSION['zoneName']]['Message'])); ?></blockquote>
      <?php } ?>
      <?php $zoneNS = $r->getZoneNS($_SESSION['zoneId']); ?>
      <?php $zoneSOA = $r->getZoneSOA($_SESSION['zoneId'], $_SESSION['zoneName']); ?>
      <table style="width:750px;">
        <tr>
          <th style="width:375px;">State of Authority</th>
          <th style="width:375px;">Name Servers</th>
        </tr>
        <tr class="row-b">
        <?php
          echo('<td>');
          echo('Name: '.$zoneSOA->Name.'<br />');
          echo('TTL: '.$zoneSOA->TTL.'<br />');
          echo('Source: '.$zoneSOA->Source.'<br />');
          echo('Contact: '.$zoneSOA->Contact.'<br />');
          echo('Serial: '.$zoneSOA->Serial.'<br />');
          echo('Refresh: '.$zoneSOA->Refresh.'<br />');
          echo('Retry: '.$zoneSOA->Retry.'<br />');
          echo('Expire: '.$zoneSOA->Expire.'<br />');
          echo('Minimum TTL: '.$zoneSOA->MinimumTTL.'<br />');
          echo('<br />');
          echo('</td>');
          echo('<td style="vertical-align:top;">');
          for ($i = 0; $i < 4; $i++) {
            echo($zoneNS->DelegationSet->NameServers->NameServer[$i]."<br />");
          }
          echo('</td>');
        ?>
        </tr>
      </table>
      <h3>Resource Records</h3>
      <table id="resourceRecords">
        <tr>
          <th class="rrAction"><div></div></th>
          <th class="rrName"><div>Name</div></th>
          <th class="rrType"><div>Type</div></th>
          <th class="rrTTL"><div>TTL</div></th>
          <th class="rrValue"><div>Value</div></th>
        </tr>
      <?php $cnt=0; foreach ($r->getZoneRR($_SESSION['zoneId']) as $zone) { ?>
      <?php $cls = ($cnt % 2) ? 'row-b' : 'row-a'; $zName = trim(preg_replace(array('/(\.)?'.$_SESSION['zoneName'].'/','/^$/','/\\\\052/'),array('','@','*'),$zone->Name)); ?>
      <?php $aryValues = ""; foreach ($zone->ResourceRecords->ResourceRecord as $resRec) { $aryValues .= $resRec->Value.";"; } ?>
        <tr class="<?php echo($cls); ?>" onClick="fillForm('<?php echo($zName); ?>','<?php echo($zone->Type); ?>','<?php echo($zone->TTL); ?>','<?php echo(base64_encode($aryValues)); ?>');">
          <td class="rrAction"><div><a href="<?php echo("?zone&name=".$zone->Name."&id=".$_SESSION['zoneId']."&type=".$zone->Type."&ttl=".$zone->TTL."&value=".urlencode($aryValues)."&delete"); ?>" title"Delete"><img src="images/delete.png" alt="X" border="0" style="clear:both;width:16px;height:16px;padding:0px;border:0px;"></a></div></td>
          <td class="rrName"><div><?php echo($zName); ?></a></div></td>
          <td class="rrType"><div><?php echo($zone->Type); ?></div></td>
          <td class="rrTTL"><div><?php echo($zone->TTL); ?></div></td>
          <td class="rrValue"><div>
          <?php
            $i = 1;
            foreach ($zone->ResourceRecords->ResourceRecord as $recValues) {
              echo("<div>");
              echo($recValues->Value);
              if ($i < count($zone->ResourceRecords->ResourceRecord)) { echo(";");}
              echo("</div>");
              $i++;
            }
          ?>
          </div></td>
        </tr>
      <?php $cnt++; } ?>
      </table>

      <h3>Create/Update Record</h3>
      <form action="<?php echo("?".$_SERVER['QUERY_STRING']."&add"); ?>" method="post" name="formRecord">
        <p>
          <div class="formBlockA">
          <label>Domain Name</label>
          <input name="rname" value="" type="text" size="30" />
          <?php echo(".".$_SESSION['zoneName']); ?> 
          </div>
          <div class="formBlockB">
          <label>Type</label>
          <select name="rtype">
            <option value="A"> A </option>
            <option value="CNAME">CNAME&nbsp;</option>
            <option value="AAAA">AAAA</option>
            <option value="MX">MX</option>
            <option value="NS">NS</option>
            <option value="PTR">PTR</option>
            <option value="SOA">SOA</option>
            <option value="SPF">SPF</option>
            <option value="SRV">SRV</option>
            <option value="TXT">TXT</option>
          </select>
          </div>
          <div class="formBlockB">
          <label>TTL</label>
          <input name="rttl" value="3600" type="text" size="6" />
          </div>
          <div class="formBlockB">
          <label>Value</label>
          <input name="rvalue" value="" type="text" size="40" />
          </div>
          <div class="formBlockB"><label>&nbsp;</label><input class="button" type="submit" value="Submit Change" /></div>
          <br /><br />
          <br /><br />
          <div class="rrHelp"><a id="v_toggle" href="#">Help?</a></div>
          <div id="vertical_slide">
          <table class="rrHelpInfo">
            <tbody>
              <tr>
                <td><b>A Format </b></td>
                <td> Value: 192.0.2.1 <br /> Value: 192.0.2.1;192.1.2.1;192.2.2.1</td>
              </tr>
              <tr>
                <td><b>AAAA Format</b></td>
                <td> Value: 2001:db8::1 </td>
              </tr>
              <tr>
                <td><b>CNAME Format</b></td>
                <td>Value: hostname.example.com</td>
              </tr>
              <tr>
                <td><b>MX Format</b></td>
                <td> Value: 10 hostname.example.com <br /> Value: 10 hostname.example.com;20 hostname.example.com</td>
              </tr>
              <tr>
                <td><b>NS Format </b></td>
                <td> Value: ns-1.example.com </td>          
              </tr>
              <tr>
                <td><b>PTR Format</b></td>
                <td> Value: hostname.example.com</td>
              </tr>
              <tr>
                <td><b>SOA Format</b></td>
                <td> Value: ns-500.awsdns-11.net hostmaster.awsdns.com 1 1 1 1 60 </td>
              </tr>
              <tr>
                <td><b>SPF Format</b></td>
                <td> An SPF record value section is the same format as a TXT format record. </td>
              </tr> 
              <tr>
                <td><b>SRV Format</b></td>
                <td> Value: 10 5 80 hostname.example.com</td>
              </tr>
              <tr>
                <td><b>TXT Format </b></td>
                <td> Value: "this is a string" <br /> Value: "a string with a \" quote in it" <br /> Value: "a string with a \100 strange character in it"<br /></td>
              </tr>
            </tbody>
          </table>
          </div>
        </p>
      </form>
      <br />
      <?php } elseif (isset($_GET['faq'])) { ?>
      <h2>Frequently Asked Questions</h2>
        <div id="accordion">
          <h3>What is MyR53DNS?</h3>
          <div class="content"><p>MyR53DNS is an open source project written in PHP designed to manage Amazon's Route 53 DNS Service.</p></div>
          <h3>What's with the versioning method?</h3>
          <div class="content"><p>YY.MM-DD(#build#)[<u>A</u>lpha/<u>B</u>eta/<u>R</u>elease]</p></div>
          <h3>Where can I find the source code for MyR53DNS?</h3>
          <div class="content"><p>The source code is being hosted on Google Code. Please visit <a target="new" href="http://myr53dns.googlecode.com">http://myr53dns.googlecode.com</a>.</p></div>
          <h3>What is AWS (Amazon Web Services)?</h3>
          <div class="content"><p>Let Amazon explain in there own words: <a href="http://aws.amazon.com/what-is-aws/">What is AWS?</a></p></div> 
          <h3>What is Route 53?</h3>
          <div class="content"><p>
            "Amazon Route 53 is a highly available and scalable DNS service designed to give developers and businesses an extremely reliable and cost effective way to route end users to Internet applications. The name for our service (Route 53) comes from the fact that DNS servers respond to queries on port 53 and provide answers that route end users to your applications on the Internet." - 
            &nbsp;<a href="http://aws.amazon.com/route53/faqs/#What_is_Route_53">What is Route 53?</a></p></div> 
          <h3>Can I trust entering my Amazon Access Key ID and Secret Access Key?</h3>
          <div class="content"><p>
            I'd like to think so. This application does not save any information of any kind (Amazon Security Credentials, Zones, Resource Records, etc.). Your Amazon Security Credentials are stored in a $_SESSION variable for the duration of the session. Once logged in, you may click "Sign Out" located on the Home link, which will clear all $_SESSION variables.
          </p></div>
          <h3>Does MyR53DNS automatically create any resource records?</h3>
          <div class="content"><p>
            MyR53DNS does not create any resource records automatically. When a zone is created, Amazon Route R3 creates an SOA record and four NS records by default.
          </p></div>
          <h3>How do I create or update a resource record?</h3>
          <div class="content"><p>
            Use the "Create/Update Record" form located at the bottom of the zone view. If the requested name and type matches an existing record, an update will be performed which consists of a DELETE+CREATE change. Otherwise a CREATE change will be processed.
          </p></div>
          <h3>How do I delete a zone or resource record?</h3>
          <div class="content"><p>
            Use the "X" next to the zone or resource records. *WARNING* Currently there is no verification prior to deleting a zone nor resource record. Make sure you intend to delete the resource. *GOOD NEWS* Amazon automatically protects from deleting a populated zone which has any resource records other than the default records.
          </p></div>
          <h3>How do I create a wildcard record? (*.example.com.)</h3>
          <div class="content"><p>
            Use an asterisk "*" for the name. When viewing the zone's resource records, the wildcard "*.example.com." will be "\052.example.com.".
          </p></div>
          <h3>How do I assign multiple IP addresses per name?</h3>
          <div class="content"><p>
            Use a semicolon ";" as a delimiter for the value. A trailing semicolon is neither problematic nor required. Ex. 1.1.1.1;2.2.2.2 or 3.3.3.3;4.4.4.4;
          </p></div>
          <h3>Configure email delivery for <a target="new" href="http://www.google.com/support/a/bin/answer.py?answer=174125">Google Apps mail servers</a></h3>
          <div class="content"><p>
            Add a new MX record for your domain with the following value: <br />
            <code>1 ASPMX.L.GOOGLE.COM;5 ALT1.ASPMX.L.GOOGLE.COM;5 ALT2.ASPMX.L.GOOGLE.COM;10 ASPMX2.GOOGLEMAIL.COM;10 ASPMX3.GOOGLEMAIL.COM</code>
          </p></div>
        </div>
        <h2>Attributions</h2>
          <ul><li><h3>Thanks to <a href="http://www.styleshout.com/free-templates.php?page=4">StyleShout</a> for the HigherGround theme and distributing it under a Creative Commons Attribution 2.5 License</h3></li></ul>
        <br />
      <?php } else { ?>
      <h2><a href="?" title="">Amazon Security Credentials</a></h2>
      <?php if (isset($_SESSION['loggedIn'])) { ?>
      <form action="?signout" method="post">
        <p>
          Currently Signed In.
          <br />
          <br />
          <input class="button" type="submit" value="Sign Out"/>
        </p>
      </form>
      <?php } else { ?>
      <form action="?signin" method="post">
        <p>
          <label>Access Key ID</label>
          <input name="key" value="" type="text" size="30" />
          <label>Secret Access Key</label>
          <input name="secret" value="" type="password" size="30" />
          <br />
          <br />
          <input class="button" type="submit" value="Sign In"/>
        </p>
      </form>
      <?php } ?>
      <?php } ?>

  <!-- main ends -->	
  </div>
				
<!-- content-wrap ends-->	
</div>

<!-- footer starts -->		
<div id="footer-wrap">
  <div id="footer-bottom">		
      MyR53DNS (2010&nbsp;-&nbsp;2011)
  </div>	
<!-- footer ends-->
</div>
<!-- wrap ends here -->
</div>

</body>
</html>
