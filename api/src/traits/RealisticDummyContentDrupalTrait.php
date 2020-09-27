<?php

namespace Drupal\realistic_dummy_content_api\traits;

use Drupal\Core\Utility\Error;
use Drupal\Core\Logger\RfcLogLevel;
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

  /**
   * Log a \Throwable to the watchdog.
   *
   * Modeled after Core's watchdog_exception().
   *
   * @param \Throwable $t
   *   A \throwable.
   * @param mixed $message
   *   The message to store in the log. If empty, a text that contains all
   *   useful information about the passed-in exception is used.
   * @param mixed $variables
   *   Array of variables to replace in the message on display or NULL if
   *   message is already translated or not possible to translate.
   * @param mixed $severity
   *   The severity of the message, as per RFC 3164.
   * @param mixed $link
   *   A link to associate with the message.
   */
  public function watchdogThrowable(\Throwable $t, $message = NULL, $variables = [], $severity = RfcLogLevel::ERROR, $link = NULL) {

    // Use a default value if $message is not set.
    if (empty($message)) {
      $message = '%type: @message in %function (line %line of %file).';
    }

    if ($link) {
      $variables['link'] = $link;
    }

    $variables += Error::decodeException($t);

    // @phpstan-ignore-next-line
    \Drupal::logger('steward_common')->log($severity, $message, $variables);
  }

}
