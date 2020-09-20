<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * The Drush log, allows function to log messages to Drush.
 */
class RealisticDummyContentDrushLog implements RealisticDummyContentLogInterface {

  /**
   * {@inheritdoc}
   */
  public function log($text, $vars = []) {
    \Drupal::logger("realistic_dummy_content_api")->notice(dt($text, $vars), 'ok');
  }

  /**
   * {@inheritdoc}
   */
  public function error($text, $vars = []) {
    $this->log('RealisticDummyContent_FAILURE');
    drush_set_error('RealisticDummyContent_ERROR', dt($text, $vars));
    // We need this for jenkins to get 1 to show up in $? With drush_die(1).
    // $? returns 0 in the command line.
    die(1);
  }

}
