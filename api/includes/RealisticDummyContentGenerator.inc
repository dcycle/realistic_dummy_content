<?php

/**
 * @file
 *
 * Define RealisticDummyContentGenerator autoload class.
 */

abstract class RealisticDummyContentGenerator {
  private $type;
  private $bundle;
  private $num;
  private $more;

  /**
   * @param $more
   *   Can contain:
   *     kill => TRUE|FALSE
   */
  function __construct($type, $bundle, $num, $more) {
    $this->type = $type;
    $this->bundle = $bundle;
    $this->num = $num;
    if (isset($more['kill']) && $more['kill']) {
      $this->kill = TRUE;
    }
    else {
      $this->kill = FALSE;
    }
  }

  function GetBundle() {
    return $this->bundle;
  }

  function GetType() {
    return $this->type;
  }

  function GetKill() {
    return $this->kill;
  }

  function GetNum() {
    return $this->num;
  }

  function Generate() {
    $this->_Generate_();
  }
}
