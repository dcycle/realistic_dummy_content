<?php

/**
 * @file
 *
 * Define RealisticDummyContentLiveEnvironment autoload class.
 */

/**
 * The live environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class represents the live environment.
 */
class RealisticDummyContentLiveEnvironment extends RealisticDummyContentEnvironment {
  /**
   * {@inheritdoc}
   */
  function _file_get_contents_($filename) {
    return file_get_contents($filename);
  }
  function _file_save_data_($data, $destination = NULL) {
    return file_save_data($data, $destination);
  }
  function _file_save_(stdClass $file) {
    return file_save($file);
  }
}
