<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentLog;

/**
 * This log class can be used whenever you need a RealisticDummyContentLog
 */
class RealisticDummyContentDebugLog implements RealisticDummyContentLog {
  public function log($text, $vars = array()) {
    debug(t($text, $vars));
  }

  public function error($text, $vars = array()) {
    debug(t($text, $vars));
  }
}

