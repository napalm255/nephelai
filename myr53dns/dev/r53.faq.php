<?php
  session_start();
  if ($_POST['login'] != 'true') {
    include('header.php');
  }
?>

  <div class="block">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Frequently Asked Questions</h2>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->

<?php
  if ($_POST['login'] != 'true') {
    include('footer.php');
  }
?>
