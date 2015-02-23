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

  /**
   * Get the contents of a file.
   *
   * @param $filename
   *   A valid filename, for example /drupal/root/sites/all/modules/your_module/realistic_dummy_content/fields/node/blog/body/03.txt
   *
   * @throws
   *   Exception
   */
  function file_get_contents($filename) {
    if (!$filename) {
      throw new Exception('Please make sure you call ' . __CLASS__ . '::' . __FUNCTION__ . '() with a valid filename');
    }
    $return = $this->_file_get_contents_($filename);
    return $return;
  }

  /**
   * Internal function used to get the contents of a file.
   *
   * Wrapper around PHP's file_get_contents() (or a simulation thereof).
   * This function will not return an exception. Please use RealisticDummyContentAPIEnv::
   * file_get_contents(), instead.
   *
   * @param $filename
   *   A valid filename, for example /drupal/root/sites/all/modules/your_module/realistic_dummy_content/fields/node/blog/body/03.txt
   *
   * @return
   *   Undefined in case the filename is invalid; otherwise returns the contents of the
   *   file.
   */
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
