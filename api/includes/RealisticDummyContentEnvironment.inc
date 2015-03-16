<?php

/**
 * @file
 *
 * Define RealisticDummyContentLiveEnvironment autoload class.
 */

/**
 * The abstract base environment.
 *
 * During normal execution, we want to do things like interact with the file-
 * system and such. However during testing we want to abstract that away. This
 * class defines abstract functions representing what the environment should
 * do.
 */
abstract class RealisticDummyContentEnvironment {
  // Private variable containing the environment to use. Calls are made directly
  // to RealisticDummyContentEnvironment's static methods, which then forward them
  // to the appropriate environment. The environment can be live, or simulated as
  // during tests. This is a form of mocking. See http://en.wikipedia.org/wiki/Mock_object
  static private $env;

  /**
   * Get the current environment.
   *
   * see the comment on the private variable $env.
   *
   * default to a live environment if none is set. (During testing, a mock environment
   * will be set here so we can better control it.)
   *
   * @return
   *   An object of type RealisticDummyContentEnvironment
   */
  static function Get() {
    if (!self::$env) {
      self::$env = new RealisticDummyContentLiveEnvironment;
    }
    return self::$env;
  }

  /**
   * Set the current environment
   *
   * See the comment on the private variable $env.
   *
   * @param $environment
   *   An object of type RealisticDummyContentEnvironment
   */
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
      throw new RealisticDummyContentException('Please use valid filename');
    }
    if (strpos($filename, '/') === FALSE) {
      throw new RealisticDummyContentException('Please use an absolute filename including its path, which must always contain at least one slash. You are using ' . $filename);
    }
    $return = $this->_file_get_contents_($filename);
    return $return;
  }

  /**
   * Internal function used to get the contents of a file.
   *
   * Wrapper around PHP's file_get_contents() (or a simulation thereof).
   * This function will not return an exception. Please use RealisticDummyContentEnvironment::
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

  /**
   * Save the file data to the real or test environment.
   *
   * @param $data
   *   The data
   * @param $destination
   *   Where to put
   *
   * @return
   *
   * @throws
   *   Exception
   */
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

  /**
   * Returns all files with a given extension for a given filepath.
   *
   * Files do not always have a one-to-one relationship with the filesystem.
   * For example:
   *
   *     1.txt
   *     2.txt
   *     3.txt
   *
   * will be represented as three files, but
   *
   *     1.txt
   *     2.txt
   *     2.txt.attribute.txt
   *     2.txt.attribute1.txt
   *     3.txt
   *
   * will also be represented as three files, but the second one will have two
   * attributes, attribute and attribute1.
   *
   * @param $filepath
   *   An absolute filepath on the system, for example /path/to/drupal/sites/all/
   *   modules/mymodule/realistic_dummy_content/fields/node/article/body
   * @param $extensions
   *   An array of extensions which should be taken into consideration.
   *
   * @return
   *   An empty array in case of an error, or an array of objects of type
   *   RealisticDummyContentFileGroup.
   */
  static function GetAllFileGroups($filepath, $extensions) {
    try {
      $candidate_files = file_scan_directory($filepath, '/.*$/', array('key' => 'filename'));

      $files = self::SortCandidateFiles($candidate_files, $extensions);

      $return = array();
      foreach ($files as $radical => $attributes) {

        $return[] = new RealisticDummyContentFileGroup($radical, isset($attributes['file']) ? $attributes['file'] : NULL, isset($attributes['attributes']) ? $attributes['attributes'] : array());
      }
      return $return;
    }
    catch (Exception $e) {
      return array();
    }
  }

  /**
   * Given a list of candidate files, sort them by names and parts.
   *
   * @param $candidate_files
   *   An array keyed by filename which contains drupal file objects, like this:
   *
   *     'one.txt' => [file object]
   *     'two.txt.attribute.txt' => [file object]
   *     'two.txt.attribute1.txt' => [file object]
   *     'three.txt' => [file object]
   *
   * @param $extensions = NULL
   *   If set, only return file groups whose base file is in one of the extenstions.
   *   For example, given an extension jpg,png, and a file structure with
   *
   *      a.jpg
   *      a.jpg.alt.txt
   *      b.txt
   *
   *   This function will return:
   *
   *      a.jpg =>
   *        file => [a.jpg]
   *        attributes =>
   *          alt => [a.jpg.alt.txt]
   *
   * @return
   *   A sorted array which looks like:
   *
   *     one.txt => array('file' => [file object]),
   *     two.txt = array(
   *       attributes => array(
   *         'attribute' => [file object],
   *         'attribute1' => [file object],
   *       )
   *     ),
   *     three.txt => array('file' => [file object]),
   *
   * @throws
   *   RealisticDummyContentException
   */
  static function SortCandidateFiles($candidate_files, $extensions = NULL) {
    foreach ($candidate_files as $candidate_filename => $candidate_file) {
      if (!is_string($candidate_filename)) {
        // Explicitly load the Exception class, because during unit tests the
        // registry is not present.
        module_load_include('inc', 'realistic_dummy_content_api', 'includes/RealisticDummyContentException');
        throw new RealisticDummyContentException('array keys should be strings');
      }
      if (!is_object($candidate_file)) {
        // Explicitly load the Exception class, because during unit tests the
        // registry is not present.
        module_load_include('inc', 'realistic_dummy_content_api', 'includes/RealisticDummyContentException');
        throw new RealisticDummyContentException('array values should be file objects');
      }
      if (strpos($candidate_filename, '/') !== FALSE) {
        // Explicitly load the Exception class, because during unit tests the
        // registry is not present.
        module_load_include('inc', 'realistic_dummy_content_api', 'includes/RealisticDummyContentException');
        throw new RealisticDummyContentException('Please do not pass file paths with slashes (/) to ' . __FUNCTION__);
      }
    }
    $return = self::SortCandidateFiles_($candidate_files, $extensions);
    return $return;
  }

  /**
   * Given a list of candidate files, sort them by names and parts.
   *
   * @param $candidate_files
   *   An array keyed by filename which contains drupal file objects. See
   *   SortCandidateFiles().
   * @param $extensions = NULL
   *   If set, extensions to filter by. See SortCandidateFiles().
   *
   * @return
   *   A sorted array. See SortCandidateFiles().
   *
   * @throws
   *   Exception
   */
  static function SortCandidateFiles_($candidate_files, $extensions = NULL) {
    $return = array();
    foreach ($candidate_files as $candidate_filename => $candidate_file) {
      if (self::validCandidateFilename($candidate_filename, $extensions)) {
        self::addFileToArray($return, $candidate_filename, $candidate_file);
      }
    }
    // We expect the files to be sorted alphabetically, which is not the case on all
    // systems.
    ksort($return);
    return $return;
  }

  static function validCandidateFilename($name, $extensions = NULL) {
    if (self::LowercaseRadicalNoExtension($name) == 'readme') {
      return FALSE;
    }
    if (!$extensions) {
      return TRUE;
    }
    $filparts = self::getFileParts($name);
    return in_array($filparts['base_extension'], $extensions);
  }

  static function getFileParts($name) {
    $return = array();
    $parts = explode('.', $name);
    if (count($parts) >= 4) {
      $return['attribute_extention'] = array_pop($parts);
      $return['attribute_name'] = array_pop($parts);
    }
    $return['base'] = implode('.', $parts);
    $return['base_extension'] = array_pop($parts);
    return $return;
  }

  static function addFileToArray(&$array, $name, $file) {
    $attribute_name = NULL;
    $attribute_extention = NULL;

    $fileinfo = self::getFileParts($name);
    if (isset($fileinfo['attribute_name'])) {
      $array[$fileinfo['base']]['attributes'][$fileinfo['attribute_name']] = $file;
    }
    else {
      $array[$fileinfo['base']]['file'] = $file;
    }
  }

  /**
   * Returns the attribute of a filename if one exists
   *
   * If >2 periods are present in the file name, then what is between the
   * last and next to last period is kept, for example:
   *
   *     a.b.c => b
   *     a.b.c.d => c
   *     a.b => NULL
   *     a => NULL
   *
   * @param $filename
   *   A filename string, for example 'a.b.txt'
   *
   * @return
   *   Null if there is attribute to extract; otherwise the attribute name, for example
   *   "b".
   *
   * @throws
   *   Exception
   */
  static function AttributeName($filename) {
    $replaced = self::Replace($filename, '\2');
    if ($replaced != $filename) {
      return $replaced;
    }
    else {
      return NULL;
    }
  }

  /**
   * Returns the name radical of a filename.
   *
   * The following examples will all return "two.txt"
   *
   *     two.txt
   *     two.txt.attribute.txt
   *     two.txt.attribute1.txt
   *
   * If >2 periods are present in the file name, then what is between the
   * last and next to last period is removed, for example:
   *
   *     a.b.c => a.c
   *     a.b.c.d => a.b.d
   *     a.b => a.b
   *     a => a
   *
   * @param $filename
   *   A filename string, for example 'a.b.txt'
   *
   * @return
   *   The name radical of this file, for example a.txt.
   *
   * @throws
   *   RealisticDummyContentException
   */
  static function FilenameRadical($filename) {
    if (!is_string($filename)) {
      throw new RealisticDummyContentException('Please pass ' . __FUNCTION__ . ' a string as a filename, not a ' . gettype($filename));
    }
    return self::Replace($filename, '\1\3');
  }

  /**
   * Returns the part of a string before the extension, in lowercase
   *
   * @param $filename
   *   A filename string, e.g. rEadmE.txt
   *
   * @return
   *   The lowercase radical without the extension, e.g. readme
   */
  static function LowercaseRadicalNoExtension($filename) {
    return drupal_strtolower(trim(preg_replace('/\.[^\.]*$/', '', $filename)));
  }

  /**
   * Returns part of a filename
   *
   * Helper function which runs a preg replace function on a filename and returns
   * the result
   *
   * @param $filename
   *   A filename, for example a, a.b, a.b.c, a.b.c.d
   * @param $replace
   *   A replacement pattern meant to be passed to preg_replace, where:
   *   \1 = everything before the next-to-last period
   *   \2 = everything between the next-to-last and last periods.
   *   \3 = everything after and including the last period
   *
   * @return
   *   The replaced filename, or the same filename in case of an error or if the
   *   pattern is not found.
   *
   * @throws
   *   RealisticDummyContentException
   */
  static function Replace($filename, $replace) {
    if (!is_string($filename)) {
      throw new RealisticDummyContentException('Please pass ' . __FUNCTION__ . ' a string as a filename, not a ' . gettype($filename));
    }
    return preg_replace('/(^.*)\.([^\.]*)(\.[^\.]*$)/', $replace, $filename);
  }

  /**
   * Returns the trimmed contents of a Drpual file object, or NULL if empty.
   *
   * @param $file
   *   A drupal file object
   *
   * @return
   *   NULL if no contents in file, or if an error occurred; otherwise a string with the
   *   trimmed contents of the file.
   */
  static function GetFileContents($file) {
    try {
      if (!is_object($file)) {
        throw new RealisticDummyContentException('Please use a file object');
      }
      return trim(self::Get()->file_get_contents($file->uri));
    }
    catch (Exception $e) {
      return NULL;
    }
  }

}
