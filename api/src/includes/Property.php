<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * Represents properties like the user picture or node titles.
 */
abstract class Property extends Attribute {

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return 'property';
  }

}
