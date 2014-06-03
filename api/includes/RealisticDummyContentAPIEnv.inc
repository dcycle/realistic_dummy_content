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
  function pathinfo($path, $options) {
    return $this->_pathinfo_($path, $options);
  }
  abstract function _pathinfo_($path, $options);
  function file_get_contents($filename) {
    return $this->_file_get_contents_($filename);
  }
  abstract function _file_get_contents_($filename);
}
