<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\cms\CMS;

/**
 * Represents the text with summary field.
 *
 * This field must have a text format when part of an entity object. Node body
 * is one example.
 */
class RealisticDummyContentTextWithSummaryField extends RealisticDummyContentField {

  /**
   * {@inheritdoc}
   */
  public function implementValueFromFile($file) {
    $value = $file->Value();
    // @TODO use the site's default, not filtered_html, as the default format.
    $format = $file->Attribute('format', 'filtered_html');
    // If the value cannot be determined, which is different from an empty
    // string.
    if ($value === NULL) {
      return NULL;
    }
    if ($value) {
      $return = CMS::instance()->formatProperty('text_with_summary',
        $value, array('format' => $format));
      return $return;
    }
    else {
      return array();
    }
  }

}
