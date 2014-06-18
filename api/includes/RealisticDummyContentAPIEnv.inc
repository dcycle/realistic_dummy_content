<?php

/**
 * @file
 *
 * Define RealisticDummyContentAPILiveEnv autoload class.
 */

/**
 * The abstract base environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class defines abstract functions representing what the environment should
 * do.
 */
abstract class RealisticDummyContentAPIEnv {
  static private $env;
  static function Get() {
    if (!self::$env) {
      self::$env = new RealisticDummyContentAPILiveEnv;
    }
    return self::$env;
  }
  static function Set($environment) {
    self::$env = $environment;
  }
  function file_get_contents($filename) {
    $return = $this->_file_get_contents_($filename);
    return $return;
  }
  abstract function _file_get_contents_($filename);
  function file_save_data($data, $destination = NULL) {
    $return = $this->_file_save_data_($data, $destination);
    return $return;
  }
  abstract function _file_save_data_($data, $destination = NULL);
  function file_save(stdClass $file) {
    $return = $this->_file_save_($file);
    return $return;
  }
  abstract function _file_save_(stdClass $file);
}
