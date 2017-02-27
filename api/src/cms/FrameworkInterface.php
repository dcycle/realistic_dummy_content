<?php

namespace Drupal\realistic_dummy_content_api\cms;

/**
 * Defines and abstracts all functions which are used by our module.
 */
interface FrameworkInterface {

  /**
   * Use devel generate to generate dummy content.
   *
   * @param array $info
   *   Can contain:
   *     entity_type: user|node
   *     node_types: array
   *     users: array of users who can own a node
   *     title_legth
   *     num: int
   *     kill: bool.
   */
  public function develGenerate($info);

  /**
   * Retrieve properties of an entity.
   *
   * Properties are different from fields. In Drupal 7, node titles are
   * properties, but field_image is a field. In Drupal 8, (almost?)
   * everything is a field.
   *
   * @param object $entity
   *   A Drupal entity object.
   *
   * @return array
   *   Array of properties.
   */
  public function entityProperties($entity);

  /**
   * Retrieve information about one field.
   *
   * @param string $name
   *   A field name.
   *
   * @return array
   *   Information about a field, corresponds to the return of
   *   field_info_field() in Drupal 7.
   */
  public function fieldInfoField($name);

  /**
   * Formats a property to add it to an entity.
   *
   * In Drupal 7, this might be array(LANGUAGE_NONE => ...); in D8 it is just
   * the file id, or value.
   *
   * @param string $type
   *   Can be 'file', 'value', ...
   * @param mixed $value
   *   A file, or string...
   * @param array $options
   *   Extra options such as the format.
   *
   * @return mixed
   *   The file data formatted for placement in an entity.
   */
  public function formatProperty($type, $value, $options = array());

  /**
   * Return the filename of a user picture.
   *
   * @param object $user
   *   A Drupal user object.
   *
   * @return string
   *   A string representing the filename of the user picture if possible.
   */
  public function userPictureFilename($user);

  /**
   * Return a Drupal 7-style field name if possible for a given entity.
   *
   * For example if the field type is entity_reference, we can transform that
   * to taxonomy_term_reference for a given field in a given entity.
   *
   * @param array $info
   *   An associative array which can contain "entity" and "field_name" and
   *   "machine_name".
   *
   * @return string
   *   A Drupal 7-style field type machine name.
   */
  public function fieldTypeMachineName($info);

  /**
   * Starts a timer, see timer_start() in Drupal 7.
   */
  public function timerStart($id);

  /**
   * Stops a timer and returns data, see timer_stop() in Drupal 7.
   */
  public function timerStop($id);

  /**
   * Return the default text filter.
   *
   * @return string
   *   filtered_html or basic_html... depending on the CMS.
   */
  public function filteredHtml();

}
