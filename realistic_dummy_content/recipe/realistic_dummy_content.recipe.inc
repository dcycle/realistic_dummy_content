<?php

/**
 * @file
 * Contains a recipe for generating realistic dummy content
 */

class realistic_dummy_content_realistic_dummy_content_recipe extends RealisticDummyContentRecipe {
  // Extending RealisticDummyContentRecipe only works for versions >=
  // 7.x-1.0-beta4 of realistic_dummy_content. For previous versions, you
  // must extend RealisticDummyContentAPIRecipe (which is also compatible
  // with versions >= 7.x-1.0-beta4).
  function _Run_() {
    // If you want sequential numbering rather than random numbering, use
    // variable_set('realistic_dummy_content_api_rand', REALISTIC_DUMMY_CONTENT_SEQUENTIAL);

    $this->NewEntities('node', 'page', 4, array('kill' => TRUE));
    $this->NewEntities('node', 'article', 10, array('kill' => TRUE));
  }
}
