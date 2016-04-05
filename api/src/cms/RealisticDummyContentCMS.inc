<?php
abstract class RealisticDummyContentCMS {
  static public function instance() {
    return new RealisticDummyContentDrupal7;
  }

  static public function hookEntityPresave($entity, $type) {
    return self::instance()->_hookEntityPresave($entity, $type);
  }
  abstract function _hookEntityPresave($entity, $type);

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
    return self::instance()->_entityIsDummy($entity, $type);
  }

  abstract function _entityIsDummy($entity, $type);

  static public function configGet($name, $default = NULL) {
    return self::instance()->_configGet($name, $default);
  }

  abstract function _configGet($name, $default);

  static public function stateGet($name, $default = NULL) {
    return self::instance()->_stateGet($name, $default);
  }

  abstract function _stateGet($name, $default);
}
