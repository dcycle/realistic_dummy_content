<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * Generic Drupal field.
 *
 * Represents a generic field which appears in an entity object as
 * array('value' => 'xyz').
 */
class RealisticDummyContentValueField extends RealisticDummyContentField {

  /**
   * {@inheritdoc}
   */
  public function implementValueFromFile($file) : array {
    $value = $file->value();
    if ($value === NULL) {
      return [];
    }
    return $this->format($value);
  }

  /**
   * Mockable wrapper around the formatter.
   *
   * @param mixed $value
   *   A file, or string.
   *
   * @return array
   *   A formatted item.
   */
  public function format($value) : array {
    return Framework::instance()->formatProperty('value', $value);
  }

}
