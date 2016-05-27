<?php
use Drupal\realistic_dummy_content_api\cms\D8;
use Drupal\realistic_dummy_content_api\cms\B1;
use Drupal\realistic_dummy_content_api\cms\D7;

namespace Drupal\realistic_dummy_content_api\cms;
/**
 *
 */
abstract class CMS {
  static private $testFlag;

  /**
   *
   */
  static public function instance() {
    $cms = realistic_dummy_content_api_version();
    switch ($cms) {
      case 'D7':
        module_load_include('php', 'realistic_dummy_content_api', 'src/cms/D7');
        return new D7();
        break;

      case 'B1':
        module_load_include('php', 'realistic_dummy_content_api', 'src/cms/B1');
        return new B1();
        break;

      case 'D8':
        return new D8();
        break;

      default:
        throw new \Exception('No CMS implementation class available for the CMS ' . $cms);
        break;
    }
  }

  /**
   *
   */
  static public function _test_instance() {
    return !is_a(self::instance(), '\Drupal\realistic_dummy_content_api\cms\CMS');
  }

  /**
   *
   */
  static public function hookEntityPresave($entity, $type) {
    $return = self::instance()->_hookEntityPresave($entity, $type);
    static::addTestFlag('hookEntityPresave called');
    return $return;
  }

  /**
   *
   */
  abstract function _hookEntityPresave($entity, $type);

  /**
   *
   */
  static public function _test_hookEntityPresave() {
    self::createEntity();
    return !static::getTestFlag('hookEntityPresave called');
  }

  /**
   *
   */
  static public function createEntity($info = array()) {
    $return = self::instance()->_createEntity($info);
    self::assertReturnedObject($return);
    return $return;
  }

  /**
   *
   */
  abstract function _createEntity($info);

  /**
   *
   */
  static public function _test_createEntity() {
    $entity = self::createEntity();
    return !is_object($entity);
  }

  /**
   *
   */
  static public function addTestFlag($flag) {
    if (!is_array(self::$testFlag)) {
      self::$testFlag = array();
    }
    self::$testFlag[$flag] = $flag;
  }

  /**
   *
   */
  static public function _test_addTestFlag() {
    self::addTestFlag('whatever');
    return !self::getTestFlag('whatever');
  }

  /**
   *
   */
  static public function getTestFlag($flag) {
    return isset(self::$testFlag[$flag]);
  }

  /**
   *
   */
  static public function clearTestFlag($flag) {
    unset(self::$testFlag[$flag]);
  }

  /**
   *
   */
  public function _test_clearTestFlag() {
    self::addTestFlag('whatever');
    self::clearTestFlag('whatever');
    return self::getTestFlag('whatever');
  }

  /**
   *
   */
  static public function _test_getTestFlag() {
    return self::_test_addTestFlag();
  }

  /**
   *
   */
  static public function setEntityProperty(&$entity, $property, $value) {
    return self::instance()->_setEntityProperty($entity, $property, $value);
  }

  /**
   *
   */
  abstract function _setEntityProperty(&$entity, $property, $value);

  /**
   *
   */
  static public function getEntityProperty(&$entity, $property) {
    return self::instance()->_getEntityProperty($entity, $property);
  }

  /**
   *
   */
  abstract function _getEntityProperty(&$entity, $property);

  /**
   *
   */
  static public function _test_getEntityProperty() {
    return self::_test_setEntityProperty();
  }

  /**
   *
   */
  static public function _test_setEntityProperty() {
    $entity = self::createEntity();
    self::setEntityProperty($entity, 'title', 'whatever');
    return self::getEntityProperty($entity, 'title') != 'whatever';
  }

  /**
   *
   */
  static public function moduleInvokeAll($hook) {
    $args = func_get_args();
    self::addTestFlag('moduleInvokeAll called');
    $object = self::instance();
    return call_user_func_array(array(&$object, '_moduleInvokeAll'), $args);
  }

  /**
   *
   */
  public function _test_moduleInvokeAll() {
    self::clearTestFlag('moduleInvokeAll called');
    realistic_dummy_content_api_is_dummy($this->createEntity(), 'node');
    return !self::getTestFlag('moduleInvokeAll called');
  }

  /**
   *
   */
  abstract function _moduleInvokeAll($hook);

  /**
   *
   */
  static public function entityIsDummy($entity, $type) {
    $return = self::instance()->_entityIsDummy($entity, $type);
    return $return;
  }

  /**
   *
   */
  abstract function _entityIsDummy($entity, $type);

  /**
   *
   */
  public function _test_entityIsDummy() {
    return $this->entityIsDummy('whatever', 'whatever') || !$this->entityIsDummy((object) array('devel_generate' => TRUE), 'whatever');
  }

  /**
   *
   */
  static public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return self::instance()->_alter($type, $data, $context1, $context2, $context3);
  }

  /**
   *
   */
  abstract function _alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL);

  /**
   *
   */
  static public function debug($message, $info = NULL) {
    return self::instance()->_debug($message, $info);
  }

  /**
   *
   */
  abstract function _debug($message, $info);

  /**
   *
   */
  static public function moduleList() {
    return self::instance()->_moduleList();
  }

  /**
   *
   */
  abstract function _moduleList();

  /**
   *
   */
  static public function fileSave($drupal_file) {
    $return = self::instance()->_fileSave($drupal_file);
    self::assertReturnedObject($return, array('fid'));
    return $return;
  }

  /**
   * Returns the calling function through a backtrace.
   */
  static function getCallingFunction() {
    // A funciton x has called a function y which called this
    // see stackoverflow.com/questions/190421.
    $caller = debug_backtrace();
    $caller = $caller[2];
    return $caller['function'];
  }

  /**
   *
   */
  static public function assertReturnedObject($data, $properties = array()) {
    $class = get_class(self::instance());
    $function = '_' . self::getCallingFunction();
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
   *
   */
  abstract function _fileSave($drupal_file);

  /**
   *
   */
  static public function getBundleName($entity) {
    return self::instance()->_getBundleName($entity);
  }

  /**
   *
   */
  abstract function _getBundleName($entity);

  /**
   *
   */
  static public function configGet($name, $default = NULL) {
    return self::instance()->_configGet($name, $default);
  }

  /**
   *
   */
  abstract function _configGet($name, $default);

  /**
   *
   */
  static public function fieldInfoFields() {
    return self::instance()->_fieldInfoFields();
  }

  /**
   *
   */
  abstract function _fieldInfoFields();

  /**
   *
   */
  static public function stateGet($name, $default = NULL) {
    return self::instance()->_stateGet($name, $default);
  }

  /**
   *
   */
  abstract function _stateGet($name, $default);

  /**
   *
   */
  static public function getAllVocabularies() {
    return self::instance()->_getAllVocabularies();
  }

  /**
   *
   */
  abstract function _getAllVocabularies();

  /**
   *
   */
  public function selfTest() {
    $all = get_class_methods(get_class($this));
    $errors = array();
    $tests = array();
    foreach ($all as $method_name) {
      if (substr($method_name, 0, 1) == '_') {
        continue;
      }
      $candidate = '_test_' . $method_name;
      if (!in_array($candidate, $all)) {
        $errors[] = 'There should be a status method called CMS::' . $candidate . '()';
      }
      else {
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
    $this->_cmsSpecificTests($errors, $tests);
    self::debug('Errors:');
    self::debug($errors);
    self::debug('Passed tests:');
    self::debug($tests);
    return count($errors) != 0;
  }

  /**
   *
   */
  function _cmsSpecificTests(&$errors, &$tests) {
  }

}
