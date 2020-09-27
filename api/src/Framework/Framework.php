<?php

namespace Drupal\realistic_dummy_content_api\Framework;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentDevelGenerateGenerator;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\user\Entity\User;

/**
 * The entry point for the framework.
 *
 * Using this class as opposed, for example, to Drupal 8-specific functions,
 * allows the code logic to work with more than one framework. Specific
 * frameworks such as Drupal 8 are represented by separate classes which
 * implement FrameworkInterface.
 */
class Framework implements FrameworkInterface {

  use StringTranslationTrait;

  /**
   * Whether we are in a test or not.
   *
   * @var mixed
   */
  static private $testFlag;

  /**
   * The Framework instance.
   *
   * @var mixed
   */
  static private $instance;

  /**
   * The Framework implementor.
   *
   * @var mixed
   */
  private $implementor;

  /**
   * Retrieves a class representing the current framework entrypoint.
   */
  public static function instance() {
    if (!self::$instance) {
      self::$instance = new Framework();
    }
    return self::$instance;
  }

  /**
   * Retrieves a framework-specific implementor.
   */
  public function implementor() {
    if (!$this->implementor) {
      $framework = realistic_dummy_content_api_version();
      switch ($framework) {
        case 'Drupal8':
          $this->implementor = new Drupal8();
          break;

        default:
          throw new \Exception('No framework implementation class available for the framework ' . $framework);
      }
    }
    return $this->implementor;
  }

  /**
   * {@inheritdoc}
   */
  public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return self::implementor()->alter($type, $data, $context1, $context2, $context3);
  }

  /**
   * {@inheritdoc}
   */
  public function configGet($name, $default = NULL) {
    return $this->implementor()->configGet($name, $default);
  }

  /**
   * {@inheritdoc}
   */
  public function createEntity(array $info = []) {
    $return = $this->implementor()->createEntity($info);
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function debug($message, $info = NULL) {
    return $this->implementor()->debug($message, $info);
  }

  /**
   * {@inheritdoc}
   */
  public function develGenerate(array $info) {
    return $this->implementor()->develGenerate($info);
  }

  /**
   * {@inheritdoc}
   */
  public function entityIsDummy($entity, $type) {
    $return = self::implementor()->entityIsDummy($entity, $type);
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function entityProperties($entity) {
    return $this->implementor()->entityProperties($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function fieldInfoField($name) {
    return $this->implementor()->fieldInfoField($name);
  }

  /**
   * {@inheritdoc}
   */
  public function fieldInfoFields() {
    return $this->implementor()->fieldInfoFields();
  }

  /**
   * {@inheritdoc}
   */
  public function fieldTypeMachineName(array $info) {
    return $this->implementor()->fieldTypeMachineName($info);
  }

  /**
   * {@inheritdoc}
   */
  public function filteredHtml() {
    return $this->implementor()->filteredHtml();
  }

  /**
   * {@inheritdoc}
   */
  public function formatProperty($type, $value, array $options = []) : array {
    return $this->implementor()->formatProperty($type, $value, $options);
  }

  /**
   * {@inheritdoc}
   */
  public function frameworkRoot() {
    return $this->implementor()->frameworkRoot();
  }

  /**
   * {@inheritdoc}
   */
  public function getAllVocabularies() {
    return $this->implementor()->getAllVocabularies();
  }

  /**
   * {@inheritdoc}
   */
  public function getBundleName($entity) {
    return $this->implementor()->getBundleName($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getEntityProperty(&$entity, $property) {
    return $this->implementor()->getEntityProperty($entity, $property);
  }

  /**
   * {@inheritdoc}
   */
  public function getPath($type, $name) {
    return $this->implementor()->getPath($type, $name);
  }

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
   * @throws \Exception
   */
  public function latestId($table = 'node', $key = 'nid') {
    // @phpstan-ignore-next-line
    return \Drupal::database()->query("SELECT $key FROM {$table} ORDER BY $key DESC LIMIT 1")->fetchField();
  }

  /**
   * Check if a module exists.
   */
  public function moduleExists($module) {
    return $this->implementor()->moduleExists($module);
  }

  /**
   * {@inheritdoc}
   */
  public function moduleInvokeAll($hook) {
    $args = func_get_args();
    self::addTestFlag('moduleInvokeAll called');
    $object = $this->implementor();
    return call_user_func_array([&$object, 'moduleInvokeAll'], $args);
  }

  /**
   * {@inheritdoc}
   */
  public function moduleList() {
    return $this->implementor()->moduleList();
  }

  /**
   * {@inheritdoc}
   */
  public function hookEntityPresave($entity) {
    $return = self::implementor()->hookEntityPresave($entity);
    static::addTestFlag('hookEntityPresave called');
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function hookUserInsert(&$edit, $account, $category) {
    return $this->implementor()->hookUserInsert($edit, $account, $category);
  }

  /**
   * {@inheritdoc}
   */
  public function hookUserPresave(&$edit, $account, $category) {
    return $this->implementor()->hookUserPresave($edit, $account, $category);
  }

  /**
   * {@inheritdoc}
   */
  public function newVocabularyTerm($vocabulary, $name) {
    return $this->implementor()->newVocabularyTerm($vocabulary, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function setEntityProperty(&$entity, $property, $value) {
    return $this->implementor()->setEntityProperty($entity, $property, $value);
  }

  /**
   * {@inheritdoc}
   */
  public function stateGet($name, $default = NULL) {
    return $this->implementor()->stateGet($name, $default);
  }

  /**
   * {@inheritdoc}
   */
  public function timerStart($id) {
    return $this->implementor()->timerStart($id);
  }

  /**
   * {@inheritdoc}
   */
  public function timerStop($id) {
    return $this->implementor()->timerStop($id);
  }

  /**
   * {@inheritdoc}
   */
  public function variableDel($variable) {
    return $this->implementor()->variableDel($variable);
  }

  /**
   * {@inheritdoc}
   */
  public function vocabularyIdentifier($vocabulary) {
    return $this->implementor()->vocabularyIdentifier($vocabulary);
  }

  /**
   * {@inheritdoc}
   */
  public function watchdog($message, $severity = 5) {
    return $this->implementor()->watchdog($message, $severity);
  }

  /**
   * Adds a flag during execution for testing.
   */
  public static function addTestFlag($flag) {
    if (!is_array(self::$testFlag)) {
      self::$testFlag = [];
    }
    self::$testFlag[$flag] = $flag;
  }

  /**
   * Clears all test flags.
   */
  public static function clearTestFlag($flag) {
    unset(self::$testFlag[$flag]);
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
  public function endToEndTests(array &$errors, array &$tests) {
    $generator = new RealisticDummyContentDevelGenerateGenerator('user', 'user', 1, ['kill' => TRUE]);
    $generator->generate();

    User::load(Framework::instance()->latestId('users', 'uid'));

    $generator = new RealisticDummyContentDevelGenerateGenerator('node', 'article', 1, ['kill' => TRUE]);
    $generator->generate();
  }

  /**
   * Perform framework-specific tests, if any.
   */
  public function frameworkSpecificTests(&$errors, &$tests) {
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
   * Retrieves whether or not a given test flag is set.
   */
  public static function getTestFlag($flag) {
    return isset(self::$testFlag[$flag]);
  }

  /**
   * Tests all functions in the class.
   */
  public function selfTest() {
    $all = get_class_methods(get_class($this));
    $errors = [];
    $tests = [];
    foreach ($all as $method_name) {
      if (substr($method_name, 0, strlen('test')) == 'test') {
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
    $this->frameworkSpecificTests($errors, $tests);
    $this->endToEndTests($errors, $tests);
    self::debug('Errors:');
    self::debug($errors);
    self::debug('Passed tests:');
    self::debug($tests);
    return count($errors) != 0;
  }

  /**
   * {@inheritdoc}
   */
  public function taxonomyLoadTree($vocabulary) {
    return $this->implementor()->taxonomyLoadTree($vocabulary);
  }

  /**
   * {@inheritdoc}
   */
  public function termId($term) {
    return $this->implementor()->termId($term);
  }

  /**
   * {@inheritdoc}
   */
  public function termName($term) {
    return $this->implementor()->termName($term);
  }

  /**
   * Tests self::addTestFlag().
   */
  public static function testAddTestFlag() {
    self::addTestFlag('whatever');
    return !self::getTestFlag('whatever');
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
   * Tests self::createEntity().
   */
  public static function testCreateEntity() {
    $entity = self::instance()->createEntity();
    return !is_object($entity);
  }

  /**
   * Tests self::entityIsDummy().
   */
  public function testEntityIsDummy() {
    return $this->entityIsDummy('whatever', 'whatever') || !$this->entityIsDummy((object) ['devel_generate' => TRUE], 'whatever');
  }

  /**
   * Tests self::getEntityProperty().
   */
  public static function testGetEntityProperty() {
    return self::testSetEntityProperty();
  }

  /**
   * Tests self::getTestFlag().
   */
  public static function testGetTestFlag() {
    return self::testAddTestFlag();
  }

  /**
   * Test self::hookEntityPresave().
   */
  public static function testHookEntityPresave() {
    self::instance()->createEntity();
    return !static::getTestFlag('hookEntityPresave called');
  }

  /**
   * Test for self::instance().
   */
  public static function testInstance() {
    return !is_a(self::instance(), '\Drupal\realistic_dummy_content_api\Framework\Framework');
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
   * Tests self::setEntityProperty().
   */
  public static function testSetEntityProperty() {
    $entity = self::instance()->createEntity();
    self::instance()->setEntityProperty($entity, 'title', 'whatever');
    return self::instance()->getEntityProperty($entity, 'title') != 'whatever';
  }

  /**
   * {@inheritdoc}
   */
  public function vocabularyMachineName($vocabulary) {
    return $this->implementor()->vocabularyMachineName($vocabulary);
  }

}
