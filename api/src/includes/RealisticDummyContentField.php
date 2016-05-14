<?php

/**
 * @file
 *
 * Define RealisticDummyContentField autoload class.
 */

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentAttribute;

/**
 * Represents fields like body or field_image.
 */
abstract class RealisticDummyContentField extends RealisticDummyContentAttribute {
  function GetType() {
    return 'field';
  }

}
