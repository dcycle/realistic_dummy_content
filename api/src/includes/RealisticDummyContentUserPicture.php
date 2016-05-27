<?php

namespace Drupal\realistic_dummy_content_api\includes;


/**
 * Represents the user picture.
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
