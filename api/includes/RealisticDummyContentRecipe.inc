<?php

/**
 * @file
 *
 * Define RealisticDummyContentRecipe autoload class.
 */

abstract class RealisticDummyContentRecipe {
  static $log;

  static function Run($log) {
    self::StartTime('run');
    self::$log = $log;
    $objects = self::FindObjects();

    foreach ($objects as $object) {
      $object->_Run_();
    }
    self::$log->log(t('Realistic dummy content generation operation completed in @time milliseconds', array('@time' => self::StopTime('run'))));
  }

  static function FindObjects() {
    $objects = array();
    // We need to cycle through all active modules and look for those
    // which contain a class module_name_realistic_dummy_content_recipe
    // in the file realistic_dummy_content/recipe/module_name.recipe.inc
    $modules = module_list();
    foreach ($modules as $module) {
      $candidate = $module . '_realistic_dummy_content_recipe';
      if (module_load_include('inc', $module, 'realistic_dummy_content/recipe/' . $module . '.recipe') && class_exists($candidate)) {
        $objects[] = new $candidate;
      }
    }
    return $objects;
  }

  /**
   * @param $more
   *   Can contain:
   *     kill => TRUE|FALSE
   */
  static function GetGenerator($type, $bundle, $count, $more) {
    if (in_array($type, array('user', 'node'))) {
      if (module_exists('devel_generate')) {
        return new RealisticDummyContentDevelGenerateGenerator($type, $bundle, $count, $more);
      }
      else {
        self::$log->Error(t('Please enable devel\'s devel_generate module to generate users or nodes.'));
      }
    }
    else {
      self::$log->Error(t('Entity types other than user and node are not supported for realistic dummy content recipe.'));
    }
  }

  function NewEntities($type, $bundle, $count, $more = array()) {
    self::StartTime(array($type, $bundle, $count));
    if ($generator = self::GetGenerator($type, $bundle, $count, $more)) {
      $generator->Generate();
    }
    else {
      self::$log->Error(t('Could not find a generator for @type @bundle.', array('@type' => $type, '@bundle' => $bundle)));
    }
    $time = self::StopTime(array($type, $bundle, $count));
    self::$log->log(t('@type @bundle: @n created in @time milliseconds', array('@type' => $type, '@bundle' => $bundle, '@n' => $count, '@time' => $time)));
  }

  static function StartTime($id) {
    timer_start(serialize($id));
  }

  static function StopTime($id) {
    $timer = timer_stop(serialize($id));
    return $timer['time'];
  }
}
