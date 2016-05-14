<?php
namespace Drupal\realistic_dummy_content_api\cms;

class D8 extends CMS {

  /**
   * {@inheritdoc}
   */
  function _hookEntityPresave($entity, $type) {
    try {
      // $type is NULL in D8; we'll compute it here.
      if (get_class($entity) == 'Drupal\file\Entity\File') {
        $type = 'file';
      }
      else {
        $type = $entity->getType();
      }
      if (realistic_dummy_content_api_is_dummy($entity, $type)) {
        $candidate = $entity;
        realistic_dummy_content_api_improve_dummy_content($candidate, $type, $filter);
        realistic_dummy_content_api_validate($candidate, $type);
      }
    }
    catch (Exception $e) {
      $this->setMessage(t('realistic_dummy_content_api failed to modify dummy objects: message: @m', array('@m' => $e->getMessage())), 'error', FALSE);
    }
  }

  public function _moduleInvokeAll($hook) {
    $args = func_get_args();
    $hook = array_shift($args);
    return \Drupal::moduleHandler()->invokeAll($hook, $args);
  }

  public function _entityIsDummy($entity, $type) {
    $return = isset($entity->devel_generate);
    return $return;
  }

  public function _fieldInfoFields() {
    return \Drupal::entityManager()->getFieldMap();
  }

  public function _moduleList() {
    $full_list = \Drupal::moduleHandler()->getModuleList();
    return array_keys($full_list);
  }

  public function _configGet($name, $default) {
    dpm('NYI ' . __LINE__);
    return;
    return variable_get($name, $default);
  }

  public function _alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return \Drupal::moduleHandler()->alter($type, $data, $context1, $context2);
  }

  public function _getBundleName($entity) {
    return $entity->bundle();
  }

  public function _stateGet($name, $default) {
    dpm('NYI ' . __LINE__);
    return;
    return variable_get($name, $default);
  }

  public function _setEntityProperty(&$entity, $property, $value) {
    if ($property == 'title' && method_exists($entity, 'setTitle')) {
      $entity->setTitle($value);
    }
  }

  public function _debug($message, $info) {
    if (is_string($message)) {
      dpm($message, $info);
    }
    else {
      dpm($info . ' ==>');
      ksm($message);
    }
  }

}
