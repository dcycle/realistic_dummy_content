<?php

/**
 * @file
 *
 * Define RealisticDummyContentEntityBase autoload class.
 */

/**
 * Generic entity manipulator.
 *
 * Class with an abstract Modify() method. Subclasses can have
 * access to entities in order to override demo content in them.
 */

abstract class RealisticDummyContentEntityBase extends RealisticDummyContentBase {
  private $hash;
  private $entity;
  private $type;
  private $filter;

  /**
   * Constructor.
   *
   * @param $entity
   *   The entity object.
   * @param $type
   *   The entity type of the object, for example user or node.
   * @param $filter
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
   */
  function __construct($entity, $type, $filter = array()) {
    $this->entity = $entity;
    $this->hash = md5(serialize($entity));
    $this->type = $type;
    $this->filter = $filter;
  }

  /**
   * Getter for the entity.
   */
  function GetEntity() {
    return $this->entity;
  }

  /**
   * Getter for the hash which uniquely identifies this entity.
   */
  function GetHash() {
    return $this->hash;
  }

  /**
   * Getter for the filter.
   */
  function GetFilter() {
    return $this->filter;
  }

  /**
   * Updates the entity object.
   *
   * Used by functions which manipulate fields and properties. Once they
   * are done with the manipulations, they update the entity using this
   * function.
   */
  function SetEntity($entity) {
    $this->entity = $entity;
  }

  /**
   * Get the entity type of the entity being manipulated.
   *
   * All entities must have a type and a bundle. The type can be node,
   * user, etc. and the bundle can be article, page. In case of a user,
   * there must be a bundle even if there is only one: it is called user,
   * like the entity type.
   *
   * @return
   *   The entity type, for example "node" or "user".
   */
  function GetType() {
    $return = $this->type;
    return $return;
  }

  /**
   * Get the bundle of the entity being manipulated.
   *
   * All entities must have a type and a bundle. The type can be node,
   * user, etc. and the bundle can be article, page. In case of a user,
   * there must be a bundle even if there is only one: it is called user,
   * like the entity type.
   *
   * @return
   *   The bundle, for example "article" or "user". Is a bundle is not
   *   readily available, return the entity type.
   */
  function GetBundle() {
    $entity = $this->GetEntity();
    if (isset($entity->type)) {
      return $entity->type;
    }
    else {
      return $this->GetType();
    }
  }

  /**
   * Modify the entity.
   *
   * Subclasses of RealisticDummyContentEntityBase need to override
   * this function to perform modifications on the entity.
   */
  abstract function Modify();

  /**
   * Check if a field should or shouldn't be manipulated.
   *
   * This concept is used especially because of a quirk in the user
   * insertion hooks: hook_user_insert() can't modify the user picture
   * whereas hook_user_presave() can modify only the picture.
   *
   * To get around this, the manipulator objects are called twice, but
   * each time filtered to change only certain parts of the user entity.
   */
  function filter($field) {
    $return = TRUE;
    $filter = $this->GetFilter();
    if (isset($filter['include'])) {
      if (!in_array($field, $filter['include'])) {
        $return = FALSE;
      }
    }
    elseif (isset($filter['exclude'])) {
      if (in_array($field, $filter['exclude'])) {
        $return = FALSE;
      }
    }
    return $return;
  }
}
