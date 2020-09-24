<?php

namespace Drupal\realistic_dummy_content_api\Framework;

/**
 * Defines and abstracts all functions which are used by our module.
 */
interface FrameworkInterface {

  /**
   * Allows third-party modules to alter data.
   */
  public function alter($type, &$data, &$context1 = NULL, &$context2 = NULL, &$context3 = NULL);

  /**
   * Gets configuration value.
   */
  public function configGet($name, $default = NULL);

  /**
   * Create an entity.
   *
   * @param array $info
   *   Associative array which can contain (defaults are the first
   *   value):
   *     entity_type => node|user|...
   *     dummy => FALSE|TRUE.
   */
  public function createEntity(array $info = []);

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
  public function develGenerate(array $info);

  /**
   * Prints debugging information.
   */
  public function debug($message, $info = NULL);

  /**
   * Checks whether an entity should be considered dummy content.
   */
  public function entityIsDummy($entity, $type);

  /**
   * Retrieve properties of an entity.
   *
   * In Drupal 8, (almost?) everything is a field. This function is here
   * for historical reasons (in Drupal 7 there were properties _and_ fields).
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
   * Get information about fields.
   */
  public function fieldInfoFields();

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
  public function fieldTypeMachineName(array $info);

  /**
   * Return the default text filter.
   *
   * @return string
   *   filtered_html or basic_html... depending on the framework.
   */
  public function filteredHtml();

  /**
   * Formats a property to add it to an entity.
   *
   * In Drupal8 it is just the file id, or value.
   *
   * @param string $type
   *   Can be 'file', 'value', ...
   * @param mixed $value
   *   A file, or string...
   * @param array $options
   *   Extra options such as the format.
   *
   * @return array
   *   The file data formatted for placement in an entity.
   */
  public function formatProperty($type, $value, array $options = []) : array;

  /**
   * Return the root path of the framework.
   */
  public function frameworkRoot();

  /**
   * Retrieves all available vocabularies.
   */
  public function getAllVocabularies();

  /**
   * Gets the bundle name of an entity.
   */
  public function getBundleName($entity);

  /**
   * Retrives the property value for an entity.
   */
  public function getEntityProperty(&$entity, $property);

  /**
   * Gets the path to a module or theme.
   */
  public function getPath($type, $name);

  /**
   * React to an entity just before it is saved.
   */
  public function hookEntityPresave($entity);

  /**
   * Hook called in D7 when a user is inserted.
   */
  public function hookUserInsert(&$edit, $account, $category);

  /**
   * Hook called in D7 when a user is saved.
   */
  public function hookUserPresave(&$edit, $account, $category);

  /**
   * Check if a module exists.
   */
  public function moduleExists($module);

  /**
   * Invokes all hooks.
   */
  public function moduleInvokeAll($hook);

  /**
   * Gets a list of modules.
   */
  public function moduleList();

  /**
   * Creates a new vocabulary term.
   *
   * @param object $vocabulary
   *   The vocabulary object.
   * @param string $name
   *   The name of the new taxonomy term.
   *
   * @return object
   *   The taxonomy term object.
   */
  public function newVocabularyTerm($vocabulary, $name);

  /**
   * Sets the property of an entity.
   */
  public function setEntityProperty(&$entity, $property, $value);

  /**
   * Gets state information.
   */
  public function stateGet($name, $default = NULL);

  /**
   * Load a taxonomy tree.
   *
   * @param object $vocabulary
   *   A Drupal vocabulary object.
   *
   * @return array
   *   An array of taxonomy term objects.
   *
   * @throws \Exception
   */
  public function taxonomyLoadTree($vocabulary);

  /**
   * Given a taxonomy term, return its id.
   *
   * @param object $term
   *   Drupal taxonomy term object.
   *
   * @return int
   *   The taxonomy term id.
   *
   * @throws \Exception
   */
  public function termId($term);

  /**
   * Given a taxonomy term, return its name.
   *
   * @param object $term
   *   Drupal taxonomy term object.
   *
   * @return string
   *   The taxonomy term name, such as "Bananas".
   *
   * @throws \Exception
   */
  public function termName($term);

  /**
   * Starts a timer, see timer_start() in Drupal 7.
   */
  public function timerStart($id);

  /**
   * Stops a timer and returns data, see timer_stop() in Drupal 7.
   */
  public function timerStop($id);

  /**
   * Delete a variable.
   */
  public function variableDel($variable);

  /**
   * Returns the unique identifier for the vocabulary.
   *
   * @param object $vocabulary
   *   The vocabulary object.
   *
   * @return mixed
   *   A unique identifier for this framework.
   */
  public function vocabularyIdentifier($vocabulary);

  /**
   * Given a vocabulary, return its name.
   *
   * @param object $vocabulary
   *   A Drupal vocabulary object.
   *
   * @return string
   *   A vocabulary machine name, such as "fruit".
   *
   * @throws \Exception
   */
  public function vocabularyMachineName($vocabulary);

  /**
   * Logs something to the watchdog.
   *
   * See watchdog() for more details on this function.
   *
   * @param mixed $message
   *   A message to log.
   * @param int $severity
   *   The litteral severity as defined in:
   *   https://api.drupal.org/api/drupal/includes%21bootstrap.inc/function/watchdog/7.x,
   *   The default being WATCHDOG_NOTICE or 5. We cannot use the constants here
   *   because PHPUnit and Drupal 8 do not know about them.
   */
  public function watchdog($message, $severity = 5);

}
