<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\includes\Recipe;

/**
 * Legacy base class for recipes.
 *
 * Included for backward-compatibility.
 */
abstract class RealisticDummyContentRecipe extends Recipe {
  // Until x.x-2.0, RealisticDummyContentRecipe was used. To avoid modifying
  // all sites which use this funcitonality, this class is included for
  // backward-compatibility.
}
