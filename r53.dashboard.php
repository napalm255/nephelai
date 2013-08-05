<?php
  session_start();
  require_once('classes/security.class.php');
  $security = new Security();
  if (!$security->checkpoint()) { header('Location: index.php'); exit(); }

  include('header.php');
?>

  <div class="block">
    <div class="block_head">
      <div class="bheadl"></div>
      <div class="bheadr"></div>
      <h2>Route 53 Dashboard</h2>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
      <div id="aws_r53_ann_rss"></div>
      <div id="aws_r53_forum_rss"></div>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->

  <script type="text/javascript">
  $(document).ready(function () {
    $('#aws_r53_ann_rss').rssfeed('https://forums.aws.amazon.com/rss/rssannounce.jspa?forumID=87', { limit : 5, snippet : false });
    $('#aws_r53_forum_rss').rssfeed('https://forums.aws.amazon.com/rss/rssmessages.jspa?forumID=87', { limit : 5, snippet : false });
  });
  </script>

<?php
  include('footer.php');
?>
