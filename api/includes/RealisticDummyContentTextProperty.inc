<?php

/**
 * @file
 *
 * Define RealisticDummyContentTextProperty autoload class.
 */

/**
 * Represents a text property like a node title or user name.
 */
class RealisticDummyContentTextProperty extends RealisticDummyContentProperty {
  /**
   * {@inheritdoc}
   */
  function ValueFromFile($contents, $extension, $file) {
    if (in_array($extension, $this->GetTextExtensions())) {
      return $contents;
    }
  }

}
