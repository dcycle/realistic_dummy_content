<?php

/**
 * @file
 *
 * Define RealisticDummyContentFieldModifier autoload class.
 */

/**
 * Represents a generic field which appears in an entity object as
 * array('value' => 'xyz').
 */
class RealisticDummyContentValueField extends RealisticDummyContentField {
  /**
   * {@inheritdoc}
   */
  function ValueFromFile($contents, $extension, $file) {
    if (in_array($extension, $this->GetTextExtensions())) {
      return array(
        LANGUAGE_NONE => array(
          array(
            'value' => $contents,
          ),
        ),
      );;
    }
  }

}
