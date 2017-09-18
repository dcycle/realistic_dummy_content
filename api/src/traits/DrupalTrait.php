<?php

namespace Drupal\realistic_dummy_content_api\traits;

use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * A trait with wrappers to system calls.
 *
 * Add this as a trait to any class, which will then be able to use the
 * methods herein, while test classes will be able to mock them.
 */
trait DrupalTrait {

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

  /**
   * Mockable wrapper around Framework::newVocabularyTerm().
   */
  public function newVocabularyTerm($vocabulary, $name) {
    return Framework::instance()->newVocabularyTerm($vocabulary, $name);
  }

  /**
   * Mockable wrapper around Framework::taxonomyLoadTree().
   */
  public function taxonomyLoadTree($vid) {
    return Framework::instance()->taxonomyLoadTree($vid);
  }

  /**
   * Mockable wrapper around Framework::termId().
   */
  public function termId($term) {
    return Framework::instance()->termId($term);
  }

  /**
   * Mockable wrapper around Framework::termName().
   */
  public function termName($term) {
    return Framework::instance()->termName($term);
  }

  /**
   * Mockable wrapper around Framework::vocabularyMachineName().
   */
  public function vocabularyMachineName($vocabulary) {
    return Framework::instance()->vocabularyMachineName($vocabulary);
  }

}
