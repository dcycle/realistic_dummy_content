<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * Field modifier for image fields.
 */
class RealisticDummyContentImageField extends RealisticDummyContentField {

  /**
   * {@inheritdoc}
   */
  public function getExtensions() {
    return $this->getImageExtensions();
  }

  /**
   * {@inheritdoc}
   */
  public function implementValueFromFile($file) : array {
    if (!$file->value()) {
      return [];
    }
    $return = [];
    $file = $this->imageSave($file);
    if ($file) {
      $return = Framework::instance()->formatProperty('file', $file);
    }
    return $return;
  }

}
