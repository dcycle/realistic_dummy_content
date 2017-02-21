<?php

namespace Drupal\realistic_dummy_content_api\cms;

use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\Entity\Term;

if (!defined('WATCHDOG_ERROR')) {
  define('WATCHDOG_ERROR', 3);
}

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
      $type = $this->getEntityType($entity);
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

  public function getEntityType($entity) {
    return $entity->getEntityType()->id();
  }

  /**
   * Perform CMS-specific tests, if any.
   */
  public function cmsSpecificTests(&$errors, &$tests) {
    $node = $this->createDummyNode();

    $result = $this->getEntityType($node);
    if ($result == 'node') {
      $tests = 'D8::getEntityType() works as expected.';
    }
    else {
      $errors = 'D8::getEntityType() returned ' . $result;
    }
  }

  public function createDummyNode() {
    $entity_type = 'node';
    $bundle = 'article';

    // get definition of target entity type
    $entity_def = \Drupal::entityManager()->getDefinition($entity_type);

    // load up an array for creation
    $new_node = array(
      //set title
      'title' => 'test node',
      $entity_def->get('entity_keys')['bundle'] => $bundle,
    );

    $new_post = \Drupal::entityManager()->getStorage($entity_type)->create($new_node);
    $new_post->save();

    return node_load($this->latestNid());
  }

  public function latestNid() {
    return db_query("SELECT nid FROM {node} ORDER BY nid DESC LIMIT 1")->fetchField();
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
        if (function_exists('ksm')) {
          ksm($message);
        }
        else {
          dpm($message);
        }
      }
    }
    $this->watchdog('<pre>' . print_r(array($info => $message), TRUE) . '</pre>');
  }

  /**
   * {@inheritdoc}
   */
  public function implementCreateEntity($info) {
    if (isset($info['entity_type']) && $info['entity_type'] != 'node') {
      throw new \Exception(__FUNCTION__ . ' unknown entity type.');
    }
    else {
      return $this->createDummyNode();
    }
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetEntityProperty(&$entity, $property) {
    $type = $this->getEntityType($entity);
    if ($type == 'node') {
      switch ($property) {
        case 'title':
          return $entity->getTitle();

        default:
          throw new \Exception(__FUNCTION__ . ' Unknown property ' . $property);
      }
    }
    throw new \Exception(__FUNCTION__ . ' not implemented for type ' . $type);
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
    $drupal_file->save();
  }

  /**
   * {@inheritdoc}
   */
  public function implementGetAllVocabularies() {
    return Vocabulary::loadMultiple();
  }

  /**
   * {@inheritdoc}
   */
  public function implementVocabularyIdentifier($vocabulary) {
    return $vocabulary->id();
  }

  /**
   * {@inheritdoc}
   */
  public function implementNewVocabularyTerm($vocabulary, $name) {
    $term = Term::create([
      'name' => $name,
      'vid' => $vocabulary->id(),
    ]);

    $term->save();

    return $term;
  }

}
