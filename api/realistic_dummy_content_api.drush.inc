<?php

/**
 * @file
 * Drush < 9 integration for the realistic_dummy_content_api module.
 */

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentDrushLog;

/**
 * Implements hook_drush_command().
 */
function realistic_dummy_content_api_drush_command() {
  $items['generate-realistic'] = [
    'description' => dt('Generates realistic dummy content by looking in each active module for a file called realistic_dummy_content/recipe/module_name.recipe.inc, which should contain a subclass of RealisticDummyContentRecipe called module_name_realistic_dummy_content_recipe with a run() method.'),
    'aliases' => ['grc'],
  ];
  return $items;
}

/**
 * Callback: Generates realistic content.
 */
function drush_realistic_dummy_content_api_generate_realistic() {
  realistic_dummy_content_api_apply_recipe(new RealisticDummyContentDrushLog());
}
