<?php

namespace Drupal\realistic_dummy_content_api\cms;

/**
 * Drupal 8-specific code.
 */
class D8 extends CMS {

  /**
   * {@inheritdoc}
   */
  public function implementHookEntityPresave($entity, $type) {
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
        realistic_dummy_content_api_improve_dummy_content($candidate, $type);
        realistic_dummy_content_api_validate($candidate, $type);
      }
    }
    catch (Exception $e) {
      $this->setMessage(t('realistic_dummy_content_api failed to modify dummy objects: message: @m', array('@m' => $e->getMessage())), 'error', FALSE);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementModuleInvokeAll($hook) {
    $args = func_get_args();
    $hook = array_shift($args);
    return \Drupal::moduleHandler()->invokeAll($hook, $args);
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
    return \Drupal::entityManager()->getFieldMap();
  }

  /**
   * {@inheritdoc}
   */
  public function implementModuleList() {
    $full_list = \Drupal::moduleHandler()->getModuleList();
    return array_keys($full_list);
  }

  /**
   * {@inheritdoc}
   */
  public function implementConfigGet($name, $default) {
    $candidate = \Drupal::config('realistic_dummy_content_api')->get($name);
    return $candidate ? $candidate : $default;
  }

  /**
   * {@inheritdoc}
   */
  public function implementAlter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return \Drupal::moduleHandler()->alter($type, $data, $context1, $context2);
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetBundleName($entity) {
    return $entity->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function implementStateGet($name, $default) {
    return \Drupal::state()->get($name, $default);
  }

  /**
   * {@inheritdoc}
   */
  public function implementSetEntityProperty(&$entity, $property, $value) {
    if ($property == 'title' && method_exists($entity, 'setTitle')) {
      $entity->setTitle($value);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementModuleExists($module) {
    return \Drupal::moduleHandler()->moduleExists($module);
  }

  /**
   * {@inheritdoc}
   */
  public function implementWatchdog($message, $severity) {
    if ($severity == WATCHDOG_ERROR) {
      \Drupal::logger('realistic_dummy_content_api')->error($message);
    }
    else {
      \Drupal::logger('realistic_dummy_content_api')->notice($message);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementDebug($message, $info) {
    if ($this->moduleExists('devel')) {
      if (is_string($message)) {
        // @codingStandardsIgnoreStart
        dpm($message, $info);
        // @codingStandardsIgnoreEnd
      }
      else {
        // @codingStandardsIgnoreStart
        dpm($info . ' ==>');
        // @codingStandardsIgnoreEnd
        ksm($message);
      }
    }
    $this->watchdog('<pre>' . print_r(array($info => $message), TRUE) . '</pre>');
  }

  /**
   * {@inheritdoc}
   */
  public function implementCreateEntity($info) {
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetEntityProperty(&$entity, $property) {
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetPath($type, $name) {
    return drupal_get_path($type, $name);
  }

  /**
   * {@inheritdoc}
   */
  public function implementCmsRoot() {
    return DRUPAL_ROOT;
  }


  /**
   * {@inheritdoc}
   */
  public function implementFileSave($drupal_file) {
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetAllVocabularies() {
  }

}
