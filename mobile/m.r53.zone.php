<?php
  session_start();
  require_once(dirname(__FILE__).'/version.php');
  require_once(dirname(__FILE__).'/classes/security.class.php');
  require_once('classes/r53.class.php');

  $security = new Security();
  if (!$security->checkpoint()) { header('Location: index.php'); exit(); }

  $r = new r53();
  $r->init();
  $r->setKey($_SESSION['awsKey']);
  $r->setSecret($_SESSION['awsSecret']);
?>
<?php include('header.php'); ?>

<!-- Page # R53 # Zone -->
<div data-role="page" id="r53zone" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1>Nephelai Mobile <br /> .: Records :.</h1>
    <a href="m.r53.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-icon="plus" data-transition="slide">New Record</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <h2><?php echo($_GET['name']); ?></h2>
    <div class="content-primary">
      <div data-role="collapsible-set" data-theme="a" data-content-theme="a" data-inset="true">
      <?php
      $cnt=0;
      foreach ($r->getZoneRR($_GET['id']) as $zone) {
        $zone->Id = $_GET['id'];
        $zName = trim(preg_replace(array('/(\.)?'.$_GET['name'].'/','/^$/','/\\\\052/'),array('','@','*'),$zone->Name));
        $aryValues = "";
        $idSave = "rr_save".$cnt; $idCopy = "rr_copy".$cnt; $idDelete = "rr_delete".$cnt;
        $idID = "rr_id".$cnt; $idName = "rr_name".$cnt; $idType = "rr_type".$cnt; $idTTL = "rr_ttl".$cnt; $idValue = "rr_value".$cnt;
        foreach ($zone->ResourceRecords->ResourceRecord as $resRec) { $aryValues .= $resRec->Value.";"; }
      ?>
        <div data-role="collapsible" data-theme="a" data-content-theme="a">
          <h3>
            <div class="ui-grid-a" data-theme="a">
              <div class="ui-block-a"><div class="ui-bar" style=""><?php echo($zName); ?></div></div>
              <div class="ui-block-b"><div class="ui-bar" style="float:right;"><?php echo($zone->Type); ?></div></div>
            </div>
          </h3>
          <p>
            <?php
            $i = 0; $rVal = '';
            foreach ($zone->ResourceRecords->ResourceRecord as $recValues) {
              $rVal .= $recValues->Value;
              if ($i < count($zone->ResourceRecords->ResourceRecord)-1) { $rVal .= ';';}
              $i++;
            }
            $zone->ResourceRecords = null;
            $zone->Value = base64_encode($rVal);
            $zone->Subdomain = preg_replace(array('/(\.)?'.$_GET['name'].'$/','/^$/','/^\\\052$/'),array('','@','*'),$zone->Name);
            $zone->Name = $_GET['name'];
            ?>
            <label>TTL</label><input type="text" style="width:100%;font-size:12px;" id="<?php echo($idTTL); ?>" value="<?php echo($zone->TTL); ?>" />
            <label data-inline="true">Value</label><textarea style="width:100%;height:100px;font-size:12px;" id="<?php echo($idValue); ?>"><?php echo($rVal); ?></textarea>
            <!--<button type="submit" data-theme="a" data-mini="true">Save</button>-->
          </p>
        </div>	
      <?php
      $cnt++;
      }
      ?>
      </div>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->

<?php include('footer.php'); ?>
