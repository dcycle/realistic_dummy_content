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
  function ValueFromFile_($file) {
    $value = $file->Value();
    // @TODO use the site's default, not filtered_html, as the default format.
    $format = $file->Attribute('format', 'filtered_html');
    // If the value cannot be determined, which is different from an empty string.
    if ($value === NULL) {
      return NULL;
    }
    if ($value) {
      return array(
        LANGUAGE_NONE => array(
          array(
            'value' => $value,
            'format' => $format,
          ),
        ),
      );
    }
    else {
      return array();
    }
  }

}
