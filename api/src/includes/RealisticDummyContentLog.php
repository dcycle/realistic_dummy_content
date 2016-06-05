<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * Interface for a log class
 */
interface RealisticDummyContentLog {
  public function log($text, $vars = array());
  public function error($text, $vars = array());
}
