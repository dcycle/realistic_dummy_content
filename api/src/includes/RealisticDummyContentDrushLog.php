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
    // @phpstan-ignore-next-line
    \Drupal::logger("realistic_dummy_content_api")->notice(dt($text, $vars));
  }

  /**
   * {@inheritdoc}
   */
  public function error($text, $vars = []) {
    $this->log('RealisticDummyContent_FAILURE');
    throw new \Exception('RealisticDummyContent_ERROR ' . dt($text, $vars));
  }

}
