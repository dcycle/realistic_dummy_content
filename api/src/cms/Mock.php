<?php
namespace Drupal\realistic_dummy_content_api\cms;
/**
 *
 */
class Mock extends CMS {

  /**
   *
   */
  function _newEntity($entity) {
    $this->_hookEntityPresave($entity, $entity->type);
    $id = rand();
    $this->entites[$id] = $entity;
    return $id;
  }

  /**
   * {@inheritdoc}
   */
  function _hookEntityPresave($entity, $type) {
    $this->print('[info] About to save ' . $this->toString($entity) . ' of type ' . $type);
    if (realistic_dummy_content_api_is_dummy($entity, $type)) {
      $this->print('[info] Determined that ' . $this->toString($entity) . ' of type ' . $type . ' is dummy, about to improve it');
      realistic_dummy_content_api_improve_dummy_content($candidate, $type, $filter);
    }
  }

  /**
   *
   */
  public function _moduleInvokeAll($hook) {
    $args = func_get_args();
    $hook = array_shift($args);
    $this->print('[info] About to let all modules apply hook ' . $hook . ' with arguments ' . serialize($args);
    if (function_exists('realistic_dummy_content_api_' . $hook)) {
      call_user_func_array('realistic_dummy_content_api_' . $hook, $args);
    }
  }

  /**
   *
   */
  public function _entityIsDummy($entity, $type) {
    $return = isset($entity->devel_generate);
    return $return;
  }

  /**
   *
   */
  public function _fieldInfoFields() {
    return array(
      'some-field-name' => array(
        'node' => array(
          'article',
        ),
      ),
    );
  }

  /**
   *
   */
  public function _moduleList() {
    return array(
      'realistic_dummy_content',
      'realistic_dummy_content_api',
    );
  }

  /**
   *
   */
  public function _configGet($name, $default) {
    return (isset($this->config[$name])) ? $this->config[$name] : $default;
  }

  /**
   *
   */
  public function _alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    if ($type == 'realistic_dummy_content_attribute_manipulator') {
      return realistic_dummy_content_api_realistic_dummy_content_attribute_manipulator_alter($data, $context1, $context2);
    }
  }

  /**
   *
   */
  public function _getBundleName($entity) {
    return $entity->bundle;
  }

  /**
   *
   */
  public function _stateGet($name, $default) {
    return (isset($this->config[$name])) ? $this->config[$name] : $default;
  }

  /**
   *
   */
  public function _setEntityProperty(&$entity, $property, $value) {
    $entity->$property = $value;
  }

  /**
   *
   */
  public function _debug($message, $info) {
    $this->print('[debug] ' . $message . ' ' . $info);
  }

}
