<?php

/**
 * @file
 * Contains a recipe for generating realistic dummy content
 */

class realistic_dummy_content_realistic_dummy_content_recipe extends RealisticDummyContentAPIRecipe {
  function _Run_() {
    $this->NewEntities('node', 'page', 4, array('kill' => TRUE));
    $this->NewEntities('node', 'article', 10, array('kill' => TRUE));
  }
}
