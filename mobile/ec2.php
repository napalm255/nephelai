<?php

  // Enable full-blown error reporting.
  error_reporting(-1);

  // Include the SDK
  require_once 'sdk/sdk.class.php';

  // Instantiate the AmazonEC2 class
  $ec2 = new AmazonEC2();

  // Get the response from a call to the DescribeImages operation.
  $res = $ec2->describe_instances();

  foreach ($res->body->reservationSet->item as $item) {
    $inst = new stdClass();
    $inst->ID = $item->instancesSet->item->instanceId;
    $inst->Name = $item->instancesSet->item->keyName;
    var_dump($inst);
  }
        
  echo $res->body->reservationSet->item[0]->instancesSet->item->instanceId;
  echo $res->body->reservationSet->item[0]->instancesSet->item->keyName;
  echo "<br/>**************************<br/>";
  // Look through the body, grab ALL <imageId> nodes, stringify the values, and filter them with the
  // PCRE regular expression.
  #$akis = $response->body->imageId()->map_string('/aki/i');
  // Display
  print_r($res);
?>
