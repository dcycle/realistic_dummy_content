<?php

namespace Drupal\realistic_dummy_content_api\includes;


/**
 * Represents a text property like a node title or user name.
 */
class RealisticDummyContentTextProperty extends RealisticDummyContentProperty {

  /**
   * {@inheritdoc}
   */
  function ValueFromFile_($file) {
    return $file->Value();
  }

}
