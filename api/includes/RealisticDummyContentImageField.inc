<?php

/**
 * @file
 *
 * Define RealisticDummyContentImageField autoload class.
 */

/**
 * Field modifier for image fields.
 */
class RealisticDummyContentImageField extends RealisticDummyContentField {
  /**
   * {@inheritdoc}
   */
  function GetExtensions() {
    return $this->GetImageExtensions();
  }

  /**
   * {@inheritdoc}
   */
  function ValueFromFile($contents, $extension, $file) {
    $file = $this->ImageSave($contents, $extension, $file);
    if ($file) {
      return array(
        LANGUAGE_NONE => array(
          (array)$file,
        ),
      );
    }
  }

}
