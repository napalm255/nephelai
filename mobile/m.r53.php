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

<!-- Page # R53 -->
<div data-role="page" id="r53" data-theme="a">
  <div data-role="header">
    <a href="m.php" data-icon="home" data-iconpos="notext" data-direction="reverse" data-transition="slide">Home</a>
    <h1>Nephelai Mobile <br /> .: Zones :.</h1>
    <a href="m.php" data-icon="back" data-iconpos="notext" data-direction="reverse" data-transition="slide">Back</a>
    <div data-role="navbar">
      <ul>
        <li><a href="#" data-icon="plus" data-transition="slide">New Zone</a></li>
      </ul>
    </div><!-- /navbar -->
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <div class="content-primary">
      <ul data-role="listview" data-autodividers="true" id="zones" data-inset="true">
    <?php
      $zones = $r->getZones();
      $cnt = 0;
      if (isset($zones)) { foreach ($zones as $zone) {
      echo('<li><a href="m.r53.zone.php?name='.$zone->Name.'&id='.str_replace('/hostedzone/','',$zone->Id).'" data-transition="slide">');
      echo('<h2>'.$zone->Name.'</h2>');
      echo('<p>'.str_replace('/hostedzone/','',$zone->Id).'</p>');
      echo('<p>'.$zone->CallerReference.'</p>');
      echo('</a></li>');
      $cnt++;
      }}
    ?>
      </ul>
      <script>
        $('ul#zones>li').tsort();
      </script>
    </div>
  </div><!-- /content -->
  <div data-role="footer">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div><!-- /page -->

<?php include('footer.php'); ?>
