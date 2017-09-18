<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * Represents fields like body or field_image.
 */
abstract class Field extends Attribute {

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return 'field';
  }

}
