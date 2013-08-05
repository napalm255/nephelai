<?php

  #
  # Global Variables
  #
  
  $notyet = "<i>unknown</i>";

  #
  # Shared Functions
  #

  function fecho ($t) { if (isset($t)) { if ($t <> "") { echo($t); } else { echo("&nbsp;-&nbsp;"); } } else { echo("&nbsp;-&nbsp;"); } }
  function recho ($t, $u) { if (isset($t)) { if ($t <> "") { echo($t); } else { echo($u); } } else { echo($u); } }
  function fempty ($t) { if (isset($t)) { if ($t <> "") { return $t; } else { return ""; } } else { return ""; } }
  function ssText ($state) { switch ($state) { case 'running': return 'stop'; break; case 'stopped': return 'start'; break; } }
  function ssIcon ($state) { switch ($state) { case 'running': return 'minus'; break; case 'stopped': return 'check'; break; } }

?>
