<?php
/**
 * @file
 *
 * Hook definitions. These functions are never called, and are included
 * here for documentation purposes only.
 */

/**
 * hook_devel_generate_extras_class().
 *
 * Return any object which is a subclass of DevelGenerateExtrasBase, which
 * will be used to modify content which is deemed to be dummy content.
 *
 * @param $bundle
 *   The bundle of the information to change, for example 'user' or 'node'.
 * @info $object
 *   The object for a given bundle, for example this can be a user object
 *   or a node object.
 *
 * @return
 *   Array of objects which are a subclass of DevelGenerateExtrasBase.
 */
function hook_devel_generate_extras_class($bundle, $object) {
  return array(
    new MySubclassOfDevelGenerateExtrasBase($bundle, $object),
    new MyOtherSubclassOfDevelGenerateExtrasBase($bundle, $object),
  );
}

/**
 * hook_devel_generate_extras_dummy().
 *
 * Return whether or not an object of a given bundle is a dummy object or not.
 * The motivation for this hook is for cases where you may not be using
 * devel_generate for nodes, or whether you have a specific technique for
 * determining whether or not a given object is dummy content or not.
 *
 * @param $bundle
 *   The bundle of the information to change, for example 'user' or 'node'.
 * @info $object
 *   The object for a given bundle, for example this can be a user object
 *   or a node object.
 *
 * @return
 *   Boolean value representing whether or not this object is a dummy object.
 */
function devel_generate_extras_devel_generate_extras_dummy($bundle, $object) {
  $return = FALSE;
  switch ($bundle) {
    case 'node':
      if (isset($info->devel_generate)) {
        return TRUE;
      }
      break;
    default:
      break;
  }
  return $return;
}
