<?php

namespace Drupal\realistic_dummy_content_api\DrushCommands;

use Drush\Commands\DrushCommands;
use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentDrushLog;

/**
 * Drush commands for Realistic Dummy Content and Drush 9+.
 */
class RealisticDummyContentDrushCommands extends DrushCommands {

  /**
   * Generates realistic dummy content.
   *
   * Generates realistic dummy content by looking in each active module for a
   * file called realistic_dummy_content/recipe/module_name.recipe.inc, which
   * should contain a subclass of RealisticDummyContentRecipe called
   * module_name_realistic_dummy_content_recipe with a run() method.
   *
   * @command realistic_dummy_content_api:generate-realistic
   * @aliases generate-realistic,grc
   * @usage realistic_dummy_content_api:generate-realistic
   *   Generates realistic dummy content by looking in each active module for a
   *   file called realistic_dummy_content/recipe/module_name.recipe.inc, which
   *   should contain a subclass of RealisticDummyContentRecipe called
   *   module_name_realistic_dummy_content_recipe with a run() method.
   */
  public function generateRealistic() {
    realistic_dummy_content_api_apply_recipe(new RealisticDummyContentDrushLog());
  }

}
