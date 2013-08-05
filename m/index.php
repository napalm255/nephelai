<?php
  session_start();
  require_once(dirname(__FILE__).'/version.php');
  require_once(dirname(__FILE__).'/classes/security.class.php');

  if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    session_write_close();
    header('Location: index.php'); exit();
  }

  if (isset($_SESSION['loggedIn'])) {
    header('Location: m.php'); exit();
  }

  if (isset($_GET['login'])) {
    if (isset($_POST['awsKey']) && (isset($_POST['awsSecret']))) {
      $_SESSION['awsKey'] = $_POST['awsKey'];
      $_SESSION['awsSecret'] = $_POST['awsSecret'];
      $_SESSION['loggedIn'] = true;
      header('Location: m.php'); exit();
    }
    header('Location: m.php'); exit();
  }
?>
<?php include('header.php'); ?>

<div data-role="page" data-theme="a"><!-- page-->
  <div data-role="header">
    <h1>Nephelai Mobile <br />.: Login :.</h1>
  </div><!-- /header -->
  <div data-role="content" data-theme="a">
    <form action="index.php?login" method="post">
      <div data-role="fieldcontain" class="ui-hide-label">
        <label for="awsKey">Key:</label>
        <input type="text" name="awsKey" id="awsKey" placeholder="Key" value=""  />
      </div>
      <div data-role="fieldcontain" class="ui-hide-label">
        <label for="awsSecret">Password:</label>
        <input type="password" name="awsSecret" id="awsSecret" placeholder="Secret" value=""  />
      </div>
      <div>
        <button type="submit" data-theme="a" data-mini="true">Sign In</button>
      </div>
    </form>
  </div>
  <div data-role="footer" data-position="fixed">
    <h4>v<?php echo($nephelai_mobile_version); ?></h4>
  </div><!-- /footer -->
</div>

<?php include('footer.php'); ?>
