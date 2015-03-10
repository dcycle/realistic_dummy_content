<?php

/**
 * @file
 *
 * Define RealisticDummyContentField autoload class.
 */

/**
 * Represents fields like body or field_image.
 */
abstract class RealisticDummyContentField extends RealisticDummyContentAttribute {
  function GetType() {
    return 'field';
  }

}
