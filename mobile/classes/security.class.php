<?php
/**
 * PHP SECURITY CLASS
 * Version 1.0
 *
 * Written by Brad Gibson
 *
 * Copyright (c) 2011 Brad Gibson
 *
 * @category NetworkManagement
 * @package overseer
 * @author Brad Gibson
 * @copyright (c) 2011 Brad Gibson
 * @revision 
 * @version 1.0
 */


class Security {
  public function login ($user, $pass) {
    
  }

  public function checkpoint () {
    $clearance = false;
    if (isset($_SESSION['loggedIn'])) {
      $clearance = true;
    }
    return $clearance;
  }
}

?>
