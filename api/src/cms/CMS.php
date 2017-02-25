<?php

namespace Drupal\realistic_dummy_content_api\cms;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentDevelGenerateGenerator;

/**
 * The abstract entry point for the CMS.
 *
 * Using this class as opposed, for example, to Drupal 7-specific functions,
 * allows the code logic to work with more than one CMS. Specific CMSs such as
 * Backdrop 1, Drupal 7 or Drupal 8, are represented by subclasses of this
 * class.
 */
abstract class CMS implements FrameworkInterface {
  static private $testFlag;

  /**
   * Retrieves a class representing the current CMS.
   */
  static public function instance() {
    $cms = realistic_dummy_content_api_version();
    switch ($cms) {
      case 'D7':
        module_load_include('php', 'realistic_dummy_content_api', 'src/cms/D7');
        return new D7();

      case 'B1':
        module_load_include('php', 'realistic_dummy_content_api', 'src/cms/B1');
        return new B1();

      case 'D8':
        return new D8();

      default:
        throw new \Exception('No CMS implementation class available for the CMS ' . $cms);
    }
  }

  /**
   * {@inheritdoc}
   */
  abstract public function develGenerate($info);

  /**
   * Test for self::instance().
   */
  static public function testInstance() {
    return !is_a(self::instance(), '\Drupal\realistic_dummy_content_api\cms\CMS');
  }

  /**
   * React to an entity just before it is saved.
   */
  static public function hookEntityPresave($entity, $type) {
    $return = self::instance()->implementHookEntityPresave($entity, $type);
    static::addTestFlag('hookEntityPresave called');
    return $return;
  }

  /**
   * Implements self::hookEntityPresave().
   */
  public abstract function implementHookEntityPresave($entity, $type);

  /**
   * Test self::hookEntityPresave().
   */
  static public function testHookEntityPresave() {
    self::createEntity();
    return !static::getTestFlag('hookEntityPresave called');
  }

  /**
   * Create an entity.
   *
   * @param array $info
   *   Associative array which can contain (defaults are the first
   *   value):
   *     entity_type => node|user|...
   *     dummy => FALSE|TRUE.
   */
  static public function createEntity($info = array()) {
    $return = self::instance()->implementCreateEntity($info);
    self::assertReturnedObject($return);
    return $return;
  }

  /**
   * Implements self::createEntity().
   */
  public abstract function implementCreateEntity($info);

  /**
   * Implements self::createEntity().
   */
  static public function testCreateEntity() {
    $entity = self::createEntity();
    return !is_object($entity);
  }

  /**
   * Adds a flag during execution for testing.
   */
  static public function addTestFlag($flag) {
    if (!is_array(self::$testFlag)) {
      self::$testFlag = array();
    }
    self::$testFlag[$flag] = $flag;
  }

  /**
   * Tests self::addTestFlag().
   */
  static public function testAddTestFlag() {
    self::addTestFlag('whatever');
    return !self::getTestFlag('whatever');
  }

  /**
   * Retrieves whether or not a given test flag is set.
   */
  static public function getTestFlag($flag) {
    return isset(self::$testFlag[$flag]);
  }

  /**
   * Clears all test flags.
   */
  static public function clearTestFlag($flag) {
    unset(self::$testFlag[$flag]);
  }

  /**
   * Tests self::clearTestFlag().
   */
  public function testClearTestFlag() {
    self::addTestFlag('whatever');
    self::clearTestFlag('whatever');
    return self::getTestFlag('whatever');
  }

  /**
   * Tests self::getTestFlag().
   */
  static public function testGetTestFlag() {
    return self::testAddTestFlag();
  }

  /**
   * Sets the property of an entity.
   */
  static public function setEntityProperty(&$entity, $property, $value) {
    return self::instance()->implementSetEntityProperty($entity, $property, $value);
  }

  /**
   * {@inheritdoc}
   */
  abstract public function formatFileProperty($file);

  /**
   * Implements self::setEntityProperty().
   */
  public abstract function implementSetEntityProperty(&$entity, $property, $value);

  /**
   * Retrives the property value for an entity.
   */
  static public function getEntityProperty(&$entity, $property) {
    return self::instance()->implementGetEntityProperty($entity, $property);
  }

  /**
   * Implements self::getEntityProperty().
   */
  public abstract function implementGetEntityProperty(&$entity, $property);

  /**
   * Tests self::getEntityProperty().
   */
  static public function testGetEntityProperty() {
    return self::testSetEntityProperty();
  }

  /**
   * Tests self::setEntityProperty().
   */
  static public function testSetEntityProperty() {
    $entity = self::createEntity();
    self::setEntityProperty($entity, 'title', 'whatever');
    return self::getEntityProperty($entity, 'title') != 'whatever';
  }

  /**
   * Invokes all hooks.
   */
  static public function moduleInvokeAll($hook) {
    $args = func_get_args();
    self::addTestFlag('moduleInvokeAll called');
    $object = self::instance();
    return call_user_func_array(array(&$object, 'implementModuleInvokeAll'), $args);
  }

  /**
   * Tests self::moduleInvokeAll().
   */
  public function testModuleInvokeAll() {
    self::clearTestFlag('moduleInvokeAll called');
    realistic_dummy_content_api_is_dummy($this->createEntity(), 'node');
    return !self::getTestFlag('moduleInvokeAll called');
  }

  /**
   * Implements self::moduleInvokeAll().
   */
  public abstract function implementModuleInvokeAll($hook);

  /**
   * Checks whether an entity should be considered dummy content.
   */
  static public function entityIsDummy($entity, $type) {
    $return = self::instance()->implementEntityIsDummy($entity, $type);
    return $return;
  }

  /**
   * Implements self::entityIsDummy().
   */
  public abstract function implementEntityIsDummy($entity, $type);

  /**
   * Tests self::entityIsDummy().
   */
  public function testEntityIsDummy() {
    return $this->entityIsDummy('whatever', 'whatever') || !$this->entityIsDummy((object) array('devel_generate' => TRUE), 'whatever');
  }

  /**
   * Allows third-party modules to alter data.
   */
  static public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return self::instance()->implementAlter($type, $data, $context1, $context2, $context3);
  }

  /**
   * Implements self::alter().
   */
  public abstract function implementAlter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL);

  /**
   * Prints debugging information.
   */
  static public function debug($message, $info = NULL) {
    return self::instance()->implementDebug($message, $info);
  }

  /**
   * Implements self::debug().
   */
  public abstract function implementDebug($message, $info);

  /**
   * Gets a list of modules.
   */
  static public function moduleList() {
    return self::instance()->implementModuleList();
  }

  /**
   * Implements self::moduleList().
   */
  public abstract function implementModuleList();

  /**
   * Check if a module exists.
   */
  static public function moduleExists($module) {
    return self::instance()->implementModuleExists($module);
  }

  /**
   * Implements self::moduleExists().
   */
  public abstract function implementModuleExists($module);

  /**
   * Logs something to the watchdog.
   *
   * See watchdog() for more details on this function.
   *
   * @param int $severity
   *   The litteral severity as defined in:
   *   https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/watchdog/7.x,
   *   The default being WATCHDOG_NOTICE or 5. We cannot use the constants here
   *   because PHPUnit does not know about them.
   */
  static public function watchdog($message, $severity = 5) {
    return self::instance()->implementWatchdog($message, $severity);
  }

  /**
   * Implements self::watchdog().
   */
  public abstract function implementWatchdog($message, $severity);

  /**
   * Saves a file to disk.
   */
  static public function fileSave($drupal_file) {
    $return = self::instance()->implementFileSave($drupal_file);
    self::assertReturnedObject($return, array('fid'));
    return $return;
  }

  /**
   * Returns the calling function through a backtrace.
   */
  public static function getCallingFunction() {
    // A funciton x has called a function y which called this
    // see stackoverflow.com/questions/190421.
    $caller = debug_backtrace();
    $caller = $caller[2];
    return $caller['function'];
  }

  /**
   * Makes sure that an implementor returns data of the correct type.
   */
  static public function assertReturnedObject($data, $properties = array()) {
    $class = get_class(self::instance());
    $function = 'implement' . ucfirst(self::getCallingFunction());
    $caller = $class . '::' . $function;

    if (!is_object($data)) {
      throw new \Exception('Please make sure ' . $caller . ' returns an object.');
    }
    foreach ($properties as $property) {
      if (!isset($data->{$property})) {
        throw new \Exception('Please make sure the object returned by ' . $caller . ' has the following property: ' . $property);
      }
    }
  }

  /**
   * Implements self::fileSave().
   */
  public abstract function implementFileSave($drupal_file);

  /**
   * Gets the bundle name of an entity.
   */
  static public function getBundleName($entity) {
    return self::instance()->implementGetBundleName($entity);
  }

  /**
   * Implements self::getBundleName().
   */
  public abstract function implementGetBundleName($entity);

  /**
   * Gets configuration value.
   */
  static public function configGet($name, $default = NULL) {
    return self::instance()->implementConfigGet($name, $default);
  }

  /**
   * Implements self::configGet().
   */
  public abstract function implementConfigGet($name, $default);

  /**
   * Gets the path to a module or theme.
   */
  static public function getPath($type, $name) {
    return self::instance()->implementGetPath($type, $name);
  }

  /**
   * Implements self::getPath().
   */
  public abstract function implementGetPath($type, $name);

  /**
   * Gets the path to a module or theme.
   */
  static public function cmsRoot() {
    return self::instance()->implementCmsRoot();
  }

  /**
   * Implements self::cmsRoot().
   */
  public abstract function implementCmsRoot();

  /**
   * {@inheritdoc}
   */
  abstract public function fieldInfoField($name);

  /**
   * Get information about fields.
   */
  public abstract function fieldInfoFields();

  /**
   * Gets state information.
   */
  static public function stateGet($name, $default = NULL) {
    return self::instance()->implementStateGet($name, $default);
  }

  /**
   * Implements self::stateGet().
   */
  public abstract function implementStateGet($name, $default);

  /**
   * Retrieves all available vocabularies.
   */
  static public function getAllVocabularies() {
    return self::instance()->implementGetAllVocabularies();
  }

  /**
   * Implements self::getAllVocabularies().
   */
  public abstract function implementGetAllVocabularies();

  /**
   * Tests all functions in the class.
   */
  public function selfTest() {
    $all = get_class_methods(get_class($this));
    $errors = array();
    $tests = array();
    foreach ($all as $method_name) {
      if (substr($method_name, 0, strlen('implement')) == 'implement' || substr($method_name, 0, strlen('test')) == 'test') {
        continue;
      }
      $candidate = 'test' . ucfirst($method_name);
      if (in_array($candidate, $all)) {
        try {
          $result = $this->{$candidate}();
          if ($result) {
            $errors[] = $candidate . ' test failed.';
          }
          else {
            $tests[] = $candidate . ' test passed.';
          }
        }
        catch (\Exception $e) {
          $errors[] = $candidate . ' threw an exception: ' . $e->getMessage();
        }
      }
    }
    $this->cmsSpecificTests($errors, $tests);
    $this->endToEndTests($errors, $tests);
    self::debug('Errors:');
    self::debug($errors);
    self::debug('Passed tests:');
    self::debug($tests);
    return count($errors) != 0;
  }

  /**
   * Run high-level tests.
   *
   * For example, create entities, make sure they have been improved with
   * realistic dummy content.
   *
   * @param array $errors
   *   Will be populated with error strings.
   * @param array $tests
   *   Will be populated with passing test strings.
   */
  public function endToEndTests(&$errors, &$tests) {
    $generator = new RealisticDummyContentDevelGenerateGenerator('user', 'user', 1, array('kill' => TRUE));
    $generator->generate();

    $user = user_load(CMS::instance()->latestId('users', 'uid'));
    if (strpos(CMS::instance()->userPictureFilename($user), 'dummyfile') !== FALSE) {
      $tests[] = 'User picture substitution OK, and aliases work correctly.';
    }
    else {
      $errors[] = 'User picture substitution does not work.';
    }

    $generator = new RealisticDummyContentDevelGenerateGenerator('node', 'article', 1, array('kill' => TRUE));
    $generator->generate();
  }

  /**
   * {@inheritdoc}
   */
  abstract public function userPictureFilename($user);

  /**
   * Retrieve the latest entity id (for example node nid).
   *
   * @param string $table
   *   A database table, for example node or users.
   * @param string $key
   *   A database key, for example nid or uid.
   *
   * @return int
   *   The latest key (node nid or user uid) in the database.
   *
   * @throws Exception
   */
  public function latestId($table = 'node', $key = 'nid') {
    return db_query("SELECT $key FROM {$table} ORDER BY $key DESC LIMIT 1")->fetchField();
  }

  /**
   * Perform CMS-specific tests, if any.
   */
  public function cmsSpecificTests(&$errors, &$tests) {
  }

  /**
   * Returns the unique identifier for the vocabulary.
   *
   * @param object $vocabulary
   *   The vocabulary object.
   *
   * @return mixed
   *   A unique identifier for this CMS.
   */
  public static function vocabularyIdentifier($vocabulary) {
    return self::instance()->implementVocabularyIdentifier($vocabulary);
  }

  /**
   * Implements self::vocabularyIdentifier().
   */
  public abstract function implementVocabularyIdentifier($vocabulary);

  /**
   * Creates a new vocabulary term.
   *
   * @param object $vocabulary
   *   The vocabulary object.
   * @param string $name
   *   The name of the new taxonomy term.
   *
   * @return object
   *   The taxonomy term object.
   */
  static public function newVocabularyTerm($vocabulary, $name) {
    return self::instance()->implementNewVocabularyTerm($vocabulary, $name);
  }

  /**
   * Implements self::newVocabularyTerm().
   */
  public abstract function implementNewVocabularyTerm($vocabulary, $name);

}
