<?php

/**
 * @file
 *
 * Define RealisticDummyContentLiveEnvironment autoload class.
 */

/**
 * The dummy environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class represents the dummy environment which can be used during unit tests.
 */
class RealisticDummyContentDummyEnvironment extends RealisticDummyContentEnvironment {
  private $files;
  function createFile($path, $data) {
    if (!is_array($this->files)) {
      $this->files = array();
    }
    $this->files[$path] = $data;
  }

  /**
   * {@inheritdoc}
   */
  function _file_get_contents_($filename) {
    if (isset($this->files[$filename])) {
      return $this->files[$filename];
    }
    trigger_error('file_get_contents(): failed to open stream');
    return NULL;
  }
  function _file_save_data_($data, $destination = NULL) {
    if (!$destination) {
      throw new Exception('the dummy file system is not designed to use null destination');
    }
    $parsed = parse_url($destination);
    $return_array = array(
      'fid' => 1,
      'uri' => $destination,
      'filename' => $parsed['host'],
    );
    return (object)$return;
  }
  function _file_save_(stdClass $file) {
  }
}
