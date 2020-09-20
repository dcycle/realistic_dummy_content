<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\Framework\Framework;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Abstract base "recipe" class.
 *
 * Recipes are sequential dummy content generators, for example if a system
 * defines "school board" and "school" content types, we might want to generate
 * five dummy school boards followed by 20 dummy schools.
 *
 * Custom modules can extend this class to create their own recipes; an example
 * can be found in this module at
 * ./realistic_dummy_content/recipe/realistic_dummy_content.recipe.inc.
 */
abstract class RealisticDummyContentRecipe {

  use StringTranslationTrait;

  /**
   * The logger.
   *
   * @var mixed
   */
  private static $log;

  /**
   * Run this recipe.
   */
  public static function run($log) {
    self::startTime('run');
    self::$log = $log;
    $objects = self::findObjects();

    foreach ($objects as $object) {
      $object->_Run_();
    }
    self::$log->log(t('Realistic dummy content generation operation completed in @time milliseconds', ['@time' => self::stopTime('run')]));
  }

  /**
   * Find all recipe objects defined by all modules.
   */
  public static function findObjects() {
    $objects = [];
    // We need to cycle through all active modules and look for those
    // which contain a class module_name_realistic_dummy_content_recipe
    // in the file realistic_dummy_content/recipe/module_name.recipe.inc.
    $modules = Framework::instance()->moduleList();
    foreach ($modules as $module) {
      $candidate = $module . '_realistic_dummy_content_recipe';
      if (self::loadRecipeClass($module) && class_exists($candidate)) {
        $objects[] = new $candidate();
      }
    }
    return $objects;
  }

  /**
   * Loads the recipe class file only if it is valid and exists.
   *
   * Version 2.x introduced the requirement that the following line should
   * be added to recipe files:
   *
   *   use
   *   Drupal\realistic_dummy_content_api\includes\RealisticDummyContentRecipe;
   *
   * So we will throw an exception warning users of previous versions to add
   * that line to use the 2.x branch of realistic_dummy_content.
   *
   * @param string $module
   *   A module name.
   *
   * @return bool|string
   *   FALSE or the full path to the loaded recipe class file.
   *
   * @throws \Exception
   */
  public static function loadRecipeClass($module) {
    $path = Framework::instance()->getPath('module', $module) . '/realistic_dummy_content/recipe/' . $module . '.recipe.inc';
    $fullpath = Framework::instance()->frameworkRoot() . '/' . $path;
    if (!file_exists($fullpath)) {
      return FALSE;
    }
    $contents = file_get_contents($fullpath);
    if (!preg_match('/use Drupal*/s', $contents)) {
      throw new \Exception('As of the 2.x version you need to add the following line to the top of your recipe at ' . $fullpath . ': use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentRecipe');
    }
    return module_load_include('inc', $module, 'realistic_dummy_content/recipe/' . $module . '.recipe');
  }

  /**
   * Return a concrete generator class to generate content.
   *
   * @param string $type
   *   An entity type such as "user" or "node".
   * @param mixed $bundle
   *   An entity bundle.
   * @param mixed $count
   *   Number of entities to generate.
   * @param array $more
   *   Can contain:
   *     kill => TRUE|FALSE.
   */
  public static function getGenerator(string $type, $bundle, $count, array $more) {
    if (in_array($type, ['user', 'node'])) {
      if (Framework::instance()->moduleExists('devel_generate')) {
        return new RealisticDummyContentDevelGenerateGenerator($type, $bundle, $count, $more);
      }
      else {
        self::$log->error(t("Please enable devel's devel_generate module to generate users or nodes."));
      }
    }
    else {
      self::$log->error(t('Entity types other than user and node are not supported for realistic dummy content recipe.'));
    }
  }

  /**
   * Create new entities.
   */
  public function newEntities($type, $bundle, $count, $more = []) {
    self::startTime([$type, $bundle, $count]);
    if ($generator = self::getGenerator($type, $bundle, $count, $more)) {
      $generator->generate();
    }
    else {
      self::$log->error($this->t('Could not find a generator for @type @bundle.', [
        '@type' => $type,
        '@bundle' => $bundle,
      ]));
    }
    $time = self::stopTime([$type, $bundle, $count]);
    self::$log->log($this->t('@type @bundle: @n created in @time milliseconds', [
      '@type' => $type,
      '@bundle' => $bundle,
      '@n' => $count,
      '@time' => $time,
    ]));
  }

  /**
   * Log the start time.
   */
  public static function startTime($id) {
    Framework::instance()->timerStart(serialize($id));
  }

  /**
   * Get the end time.
   */
  public static function stopTime($id) {
    $timer = Framework::instance()->timerStop(serialize($id));
    return $timer['time'];
  }

}
