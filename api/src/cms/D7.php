<?php
use Drupal\realistic_dummy_content_api\cms\CMS;

namespace Drupal\realistic_dummy_content_api\cms;
/**
 *
 */
class D7 extends CMS {

  /**
   *
   */
  function _hookEntityPresave($entity, $type) {
    if ($type != 'user') {
      $this->_realistic_dummy_content_api_entity_presave($entity, $type);
    }
  }

  /**
   *
   */
  public function hookUserInsert(&$edit, $account, $category) {
    static::addTestFlag('hookUserInsert called');
    return $this->instance()->_hookUserInsert($edit, $account, $category);
  }

  /**
   *
   */
  public function hookUserPresave(&$edit, $account, $category) {
    static::addTestFlag('hookUserPresave called');
    return $this->instance()->_hookUserPresave($edit, $account, $category);
  }

  /**
   *
   */
  function _hookUserInsert(&$edit, $account, $category) {
    // This hook is invoked only once when the user is first created, whether
    // by the administrator or by devel_generate. The hook is not invoked
    // thereafter.
    $filter = array(
      'exclude' => array(
        'picture',
      ),
    );
    $this->_realistic_dummy_content_api_entity_presave($account, 'user', $filter);
  }

  /**
   *
   */
  function _cmsSpecificTests(&$errors, &$tests) {
  }

  /**
   *
   */
  function _hookUserPresave(&$edit, $account, $category) {
    // This hook is called when content is updated, in which case we don't want
    // to tamper with it. When content is first created, the $account's is_new
    // property is set to FALSE, se we can't depend on that to determine whether
    // the user is new or not. However, $edit['picture_delete'] is _only_ set
    // when users are updated, so we can check for that to determine whether or
    // not to continue modifying the account.
    if (isset($edit['picture_delete'])) {
      // Not a new account, don't mess with it.
      return;
    }
    // At this point we know we are dealing with a new user.
    // $edit['uid'] can have several values:
    // This hook is invoked twice when content is created via devel_generate,
    // once with $edit['uid'] set to NULL (which causes us to do nothing) and
    // once with $edit['uid'] set to the UID of newly-created user object.
    // When the user is changed via the admin interface, this hook is invoked
    // but $edit['uid'] is not set. $edit['uid'] is never set during testing,
    // so we use $account->uid instead. $account->uid is set whether we are
    // creating the user in our test code or it's created via devel_generate.
    if (isset($account->uid) && $account->uid) {
      $filter = array(
        'include' => array(
          'picture',
        ),
      );
      $user = (object) $edit;
      $this->_realistic_dummy_content_api_entity_presave($user, 'user', $filter);
      $edit = (array) $user;
    }
  }

  /**
   * Generic function called by various hooks in Drupal.
   *
   * Hook_entity_insert(), hook_user_insert() and hook_user_presave() have
   * subtle differences. This function aims to be more abstract and uses the
   * concept of a filter, see below.
   *
   * @param object $entity
   *   The object for a given type, for example this can be a user object
   *   or a node object.
   * @param string $type
   *   The entity type of the information to change, for example 'user' or
   *   'node'.
   * @param array $filter
   *   If set, only certain fields will be considered when manipulating
   *   the object. This can be useful, for example for users, because
   *   two separate manipulations need to be performed, depending on whether
   *   hook_user_insert() or hook_user_presave(). Both hooks need to modify
   *   only certain properties and fields, but taken together the entire
   *   object can be manipulated.
   *   The filter is an associative array which can contain no key (all
   *   fields and properties should be manipulated), the include key (fields
   *   included are the only ones to be manipulated, or the exclude key (all
   *   fields except those included are the ones to be manipulated).
   *
   *   realistic_dummy_content_api_user_insert() defines the array
   *   ('exclude' => array(picture)) whereas
   *   realistic_dummy_content_api_user_presave() defines the array
   *   ('include' => array(picture)). Therefore taken together these two
   *   hooks manipulate the entire user object, but in two phases.
   *
   *   This allows hook implementations to return a different class based on
   *   the type of filter.
   */
  function _realistic_dummy_content_api_entity_presave($entity, $type, $filter = array()) {
    try {
      if (realistic_dummy_content_api_is_dummy($entity, $type)) {
        $candidate = $entity;
        realistic_dummy_content_api_improve_dummy_content($candidate, $type, $filter);
        realistic_dummy_content_api_validate($candidate, $type);
        // $entity = $candidate;.
      }
    }
    catch (\Exception $e) {
      drupal_set_message(t('realistic_dummy_content_api failed to modify dummy objects: message: @m', array('@m' => $e->getMessage())), 'error', FALSE);
    }
  }

  /**
   *
   */
  public function _alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL) {
    return drupal_alter($type, $data, $context1, $context2, $context3);
  }

  /**
   *
   */
  public function _fieldInfoFields() {
    return field_info_fields();
  }

  /**
   *
   */
  public function _moduleList() {
    return module_list();
  }

  /**
   *
   */
  public function _moduleInvokeAll($hook) {
    $args = func_get_args();
    $object = self::instance();
    return call_user_func_array('module_invoke_all', $args);
  }

  /**
   *
   */
  public function _entityIsDummy($entity, $type) {
    $return = FALSE;
    // Any entity with the devel_generate property set should be considered
    // dummy content. although not all dummy content has this flag set.
    // See https://drupal.org/node/2252965
    // See https://drupal.org/node/2257271
    if (isset($entity->devel_generate)) {
      return TRUE;
    }
    switch ($type) {
      case 'user':
        // devel_generate puts .invalid at the end of the generated user's
        // email address. This module should not be activated on a production
        // site, or else anyone can put ".invalid" at the end of their email
        // address and their profile's content will be overridden.
        $suffix = '.invalid';
        if (isset($entity->mail) && drupal_substr($entity->mail, strlen($entity->mail) - strlen($suffix)) == $suffix) {
          return TRUE;
        }
        break;

      default:
        break;
    }
    return $return;
  }

  /**
   *
   */
  public function _getBundleName($entity) {
    return $entity->type;
  }

  /**
   *
   */
  public function _configGet($name, $default) {
    return variable_get($name, $default);
  }

  /**
   *
   */
  public function _stateGet($name, $default) {
    return variable_get($name, $default);
  }

  /**
   *
   */
  public function _setEntityProperty(&$entity, $property, $value) {
    $entity->{$property} = $value;
  }

  /**
   *
   */
  public function _getEntityProperty(&$entity, $property) {
    return $entity->{$property};
  }

  /**
   *
   */
  public function _createEntity($info) {
    if (isset($info['entity_type'])) {
      $entity_type = $info['entity_type'];
    }
    else {
      $entity_type = 'node';
    }
    $entity = new \stdClass();
    switch ($entity_type) {
      case 'node':
        $entity->title = rand(100000, 999999);
        $entity->type = $this->getDefaultNodeType();
        node_save($entity);
        break;

      case 'user':
        $entity = user_save(drupal_anonymous_user(), array('name' => rand(1000000, 9999999)));
        break;

      default:
        throw new \Exception('Unknown entity type ' . $entity_type);
        break;
    }
    return $entity;
  }

  /**
   *
   */
  public function getDefaultNodeType() {
    return 'article';
  }

  /**
   *
   */
  public function _test_getDefaultNodeType() {
    return !is_string($this->getDefaultNodeType());
  }

  /**
   *
   */
  public function _debug($message, $info) {
    dpm($message, $info);
  }

  /**
   *
   */
  public function _getAllVocabularies() {
    $return = taxonomy_get_vocabularies();
    return $return;
  }

  /**
   *
   */
  function _fileSave($drupal_file) {
    return file_save($drupal_file);
  }

  /**
   *
   */
  function _test_hookUserInsert() {
    self::clearTestFlag('hookUserInsert called');
    self::createEntity(array('entity_type' => 'user'));
    return !static::getTestFlag('hookUserInsert called');
  }

  /**
   *
   */
  function _test_hookUserPresave() {
    self::clearTestFlag('hookUserPresave called');
    self::createEntity(array('entity_type' => 'user'));
    return !static::getTestFlag('hookUserPresave called');
  }

}
