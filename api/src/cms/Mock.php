<?php
namespace Drupal\realistic_dummy_content_api\cms;

/**
 * Represents a mock CMS.
 *
 * Because all CMS-specific code is abstracted out of our module's logic, we are
 * able to implement a mock CMS which can be used to test our code's logic
 * outside of the context of a specific CMS.
 */
class Mock extends CMS {

  /**
   * {@inheritdoc}
   */
  public function implementNewEntity($entity) {
    $this->implementHookEntityPresave($entity, $entity->type);
    $id = rand();
    $this->entites[$id] = $entity;
    return $id;
  }

  /**
   * {@inheritdoc}
   */
  public function implementHookEntityPresave($entity, $type) {
    $this->print('[info] About to save ' . $this->toString($entity) . ' of type ' . $type);
    if (realistic_dummy_content_api_is_dummy($entity, $type)) {
      $this->print('[info] Determined that ' . $this->toString($entity) . ' of type ' . $type . ' is dummy, about to improve it');
      realistic_dummy_content_api_improve_dummy_content($entity, $type);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementModuleInvokeAll($hook) {
    $args = func_get_args();
    $hook = array_shift($args);
    $this->print('[info] About to let all modules apply hook ' . $hook . ' with arguments ' . serialize($args);
    if (function_exists('realistic_dummy_content_api_' . $hook)) {
      call_user_func_array('realistic_dummy_content_api_' . $hook, $args);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementEntityIsDummy($entity, $type) {
    $return = isset($entity->devel_generate);
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function implementFieldInfoFields() {
    return array(
      'some-field-name' => array(
        'node' => array(
          'article',
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function implementModuleList() {
    return array(
      'realistic_dummy_content',
      'realistic_dummy_content_api',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function implementConfigGet($name, $default) {
    return (isset($this->config[$name])) ? $this->config[$name] : $default;
  }

  /**
   * {@inheritdoc}
   */
  public function implementAlter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    if ($type == 'realistic_dummy_content_attribute_manipulator') {
      return realistic_dummy_content_api_realistic_dummy_content_attribute_manipulator_alter($data, $context1, $context2);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetBundleName($entity) {
    return $entity->bundle;
  }

  /**
   * {@inheritdoc}
   */
  public function implelentStateGet($name, $default) {
    return (isset($this->config[$name])) ? $this->config[$name] : $default;
  }

  /**
   * {@inheritdoc}
   */
  public function implementSetEntityProperty(&$entity, $property, $value) {
    $entity->$property = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function implementDebug($message, $info) {
    $this->print('[debug] ' . $message . ' ' . $info);
  }

}
