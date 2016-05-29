<?php

namespace Drupal\realistic_dummy_content_api\includes;

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
  public function implementValueFromFile($file) {
    if (!$file->value()) {
      return NULL;
    }
    $return = NULL;
    $file = $this->imageSave($file);
    if ($file) {
      $return = array(
        LANGUAGE_NONE => array(
          (array) $file,
        ),
      );
    }
    return $return;
  }

}
