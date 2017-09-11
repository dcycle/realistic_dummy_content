<?php

namespace Drupal\realistic_dummy_content_api\traits;

use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * A trait with wrappers to system calls.
 *
 * Add this as a trait to any class, which will then be able to use the
 * methods herein, while test classes will be able to mock them.
 */
trait RealisticDummyContentDrupalTrait {

  /**
   * Mockable wrapper around Framework::fieldInfoField().
   */
  public function fieldInfoField($field_name) {
    return Framework::instance()->fieldInfoField($field_name);
  }

  /**
   * Mockable wrapper around Framework::getAllVocabularies().
   */
  public function getAllVocabularies() {
    return Framework::instance()->getAllVocabularies();
  }

}
