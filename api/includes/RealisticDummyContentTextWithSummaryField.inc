<?php

/**
 * @file
 *
 * Define RealisticDummyContentTextWithSummaryField autoload class.
 */

/**
 * Represents the text with summary field, which must have a text format when part
 * of an entity object. Node body is one example.
 */
class RealisticDummyContentTextWithSummaryField extends RealisticDummyContentField {
  /**
   * {@inheritdoc}
   */
  function ValueFromFile($contents, $extension, $file) {
    if (in_array($extension, $this->GetTextExtensions())) {
      return array(
        LANGUAGE_NONE => array(
          array(
            'value' => $contents,
            // @TODO allow other formats
            'format' => 'filtered_html',
          ),
        ),
      );
    }
  }

}
