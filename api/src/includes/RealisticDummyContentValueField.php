<?php

namespace Drupal\realistic_dummy_content_api\includes;



/**
 * Represents a generic field which appears in an entity object as
 * array('value' => 'xyz').
 */
class RealisticDummyContentValueField extends RealisticDummyContentField {

  /**
   * {@inheritdoc}
   */
  function ValueFromFile_($file) {
    $value = $file->Value();
    if ($value === NULL) {
      return;
    }
    return array(
      LANGUAGE_NONE => array(
        array(
          'value' => $value,
        ),
      ),
    );;
  }

}
