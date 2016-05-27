<?php

namespace Drupal\realistic_dummy_content_api\includes;


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
  function ValueFromFile_($file) {
    if (!$file->Value()) {
      return NULL;
    }
    $return = NULL;
    $file = $this->ImageSave($file);
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
