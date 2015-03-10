<?php

/**
 * @file
 *
 * Define RealisticDummyContentProperty autoload class.
 */

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
