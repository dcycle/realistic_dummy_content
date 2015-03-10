<?php

/**
 * @file
 *
 * Define RealisticDummyContentUserPicture autoload class.
 */

/**
 * Represents the user picture
 */
class RealisticDummyContentUserPicture extends RealisticDummyContentProperty {
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
      return $file;
    }
  }

}
