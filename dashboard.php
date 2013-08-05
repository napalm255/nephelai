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
      <h2>Dashboard</h2>
    </div>  <!-- .block_head ends -->
    <div class="block_content">
    <div id="aws_whatsnew_rss"></div>
    <div id="aws_blog_rss"></div>
    <div id="aws_forum_rss"></div>
    </div>  <!-- .block_content ends -->
    <div class="bendl"></div>
    <div class="bendr"></div>
  </div>  <!-- .block ends -->
  <script type="text/javascript">
  $(document).ready(function () {
    $('#aws_whatsnew_rss').rssfeed('https://aws.amazon.com/rss/whats-new.rss', { limit : 5, snippet : true });
    $('#aws_blog_rss').rssfeed('http://feeds.feedburner.com/AmazonWebServicesBlog', { limit : 5, snippet : true });
    $('#aws_forum_rss').rssfeed('https://forums.aws.amazon.com/rss/rssmessages.jspa?categoryID=3', { limit : 5, snippet : false });
  });
  </script>


<?php
  include('footer.php');
?>
