<?php

/**
 * @file
 *
 * Define RealisticDummyContentProperty autoload class.
 */

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentAttribute;

/**
 * Represents properties like the user picture or node titles.
 */
abstract class RealisticDummyContentProperty extends RealisticDummyContentAttribute {
  /**
   * {@inheritdoc}
   */
  function GetType() {
    return 'property';
  }

}
