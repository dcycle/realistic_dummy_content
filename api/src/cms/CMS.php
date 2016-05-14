<?php
namespace Drupal\realistic_dummy_content_api\cms;

abstract class CMS {
  static public function instance() {
    if (defined('VERSION')) {
      module_load_include('php', 'realistic_dummy_content_api', 'src/cms/D7');
      return new \Drupal\realistic_dummy_content_api\cms\D7;
    }
    if (defined('Drupal::VERSION')) {
      return new \Drupal\realistic_dummy_content_api\cms\D8;
    }
  }

  static public function hookEntityPresave($entity, $type) {
    return self::instance()->_hookEntityPresave($entity, $type);
  }
  abstract function _hookEntityPresave($entity, $type);

  static public function setEntityProperty(&$entity, $property, $value) {
    return self::instance()->_setEntityProperty($entity, $property, $value);
  }
  abstract function _setEntityProperty(&$entity, $property, $value);

  static public function hookUserInsert(&$edit, $account, $category) {
    return self::instance()->_hookUserInsert($edit, $account, $category);
  }
  function _hookUserInsert(&$edit, $account, $category) {
  }

  static public function hookUserPresave(&$edit, $account, $category) {
    return self::instance()->_hookUserPresave($edit, $account, $category);
  }
  function _hookUserPresave(&$edit, $account, $category) {
  }

  static public function moduleInvokeAll($hook) {
    $args = func_get_args();
    $object = self::instance();
    return call_user_func_array(array(&$object, '_moduleInvokeAll'), $args);
  }

  abstract function _moduleInvokeAll($hook);

  static public function entityIsDummy($entity, $type) {
    $return = self::instance()->_entityIsDummy($entity, $type);
    return $return;
  }

  abstract function _entityIsDummy($entity, $type);

  static public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return self::instance()->_alter($type, $data, $context1, $context2, $context3);
  }

  abstract function _alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL);

  static public function debug($message, $info = NULL) {
    return self::instance()->_debug($message, $info);
  }

  abstract function _debug($message, $info);

  static public function moduleList() {
    return self::instance()->_moduleList();
  }

  abstract function _moduleList();

  static public function getBundleName($entity) {
    return self::instance()->_getBundleName($entity);
  }

  abstract function _getBundleName($entity);

  static public function configGet($name, $default = NULL) {
    return self::instance()->_configGet($name, $default);
  }

  abstract function _configGet($name, $default);

  static public function fieldInfoFields() {
    return self::instance()->_fieldInfoFields();
  }

  abstract function _fieldInfoFields();

  static public function stateGet($name, $default = NULL) {
    return self::instance()->_stateGet($name, $default);
  }

  abstract function _stateGet($name, $default);
}
