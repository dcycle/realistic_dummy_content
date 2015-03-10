<?php
/**
 * @file
 *
 * Hook definitions. These functions are never called, and are included
 * here for documentation purposes only.
 */

/**
 * @param $machine_name
 *   The machine name of the field or property, for example "title",
 *   "text_with_summary", or "picture".
 */
function hook_realistic_dummy_content_attribute_manipulator_alter(&$class, &$type, &$machine_name) {
  // If you want to implement a particular manipulator class for a field or property
  // you can do so by implementing this hook and reproducing what's below for your
  // own field or property type.
  switch ($machine_name) {
    case 'picture': // the user picture
      $class = 'RealisticDummyContentUserPicture';
      break;
    case 'text_with_summary': // e.g. body
      $class = 'RealisticDummyContentTextWithSummaryField';
      break;
    case 'taxonomy_term_reference': // e.g. tags on articles
      $class = 'RealisticDummyContentTermReferenceField';
      break;
    case 'image': // e.g. images on articles
      $class = 'RealisticDummyContentImageField';
      break;
    default:
      break;
  }
}

/**
 * hook_realistic_dummy_content_api_class().
 *
 * Return any object which is a subclass of RealisticDummyContentBase, which
 * will be used to modify content which is deemed to be dummy content.
 *
 * @param $entity
 *   The object for a given type, for example this can be a user object
 *   or a node object.
 * @param $type
 *   The entity type of the information to change, for example 'user' or 'node'.
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
 *
 *   This allows hook implementations to return a different class based on
 *   the type of filter.
 *
 * @return
 *   Array of objects which are a subclass of RealisticDummyContentBase.
 */
function hook_realistic_dummy_content_api_class($entity, $type, $filter = array()) {
  return array(
    // Insert class names for all classes which can modify entities for the
    // given type. These classes must exist, either through Drupal's
    // autoload system or be included explictely, and they must be
    // subclasses of RealisticDummyContentBase
    'RealisticDummyContentFieldModifier',
  );
}

/**
 * hook_realistic_dummy_content_api_dummy().
 *
 * Return whether or not an object of a given type is a dummy object or not.
 * The motivation for this hook is for cases where you may not be using
 * devel_generate for nodes, or whether you have a specific technique for
 * determining whether or not a given object is dummy content or not.
 *
 * @param $entity
 *   The object for a given type, for example this can be a user object
 *   or a node object.
 * @param $type
 *   The type of the information to change, for example 'user' or 'node'.
 *
 * @return
 *   Boolean value representing whether or not this object is a dummy object.
 *   FALSE means we were unable to ascertain that the entity is in fact
 *   a dummy object. Other modules which implement this hook might
 *   determine that this is a dummy object.
 */
function hook_realistic_dummy_content_api_dummy($entity, $type) {
  $return = FALSE;
  switch ($type) {
    case 'node':
      if (isset($entity->devel_generate)) {
        return TRUE;
      }
      break;
    case 'user':
      // devel_generate puts .invalid at the end of the generated user's
      // email address. This module should not be activated on a production
      // site, or else anyone can put ".invalid" at the end of their email
      // address and their profile's content will be overridden.
      $suffix = '.invalid';
      if (drupal_substr($entity->mail, strlen($entity->mail) - strlen($suffix)) == $suffix) {
        return TRUE;
      }
      break;
    default:
      break;
  }
  return $return;
}
