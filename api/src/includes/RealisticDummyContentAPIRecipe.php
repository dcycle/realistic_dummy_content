<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\includes\Recipe;

/**
 * Legacy base class for recipes.
 *
 * Included for backward-compatibility.
 */
abstract class RealisticDummyContentAPIRecipe extends Recipe {
  // Prior to beta4, RealisticDummyContentAPIRecipe was used. To avoid modifying
  // all sites which use this funcitonality, this class is included for
  // backward-compatibility with beta3 (see
  // https://www.drupal.org/node/2451125).
}
