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
  function ValueFromFile_($file) {
    $file = $this->ImageSave($file);
    if ($file) {
      return $file;
    }
  }

}
