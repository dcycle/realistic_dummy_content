<?php

/**
 * @file
 *
 * Define RealisticDummyContentAPILiveEnv autoload class.
 */

/**
 * The live environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class represents the live environment.
 */
class RealisticDummyContentAPILiveEnv extends RealisticDummyContentAPIEnv {
  function _pathinfo_($path, $options) {
    return pathinfo($path, $options);
  }
  function _file_get_contents_($filename) {
    return file_get_contents($filename);
  }
}
