<?php

/**
 * @file
 *
 * Define RealisticDummyContentAPIFieldModifier autoload class.
 */

/**
 * Field modifier class.
 *
 * All manipulation of generated content to make it more realistic
 * passes through modifiers (direct or indirect subclasses of
 * RealisticDummyContentAPIEntityBase).
 *
 * This class allows active modules to put files in a specific
 * directory hierarchy resembling realistic_dummy_content/fields/
 * [entity_type]/[bundle]/[field_name], and for these files to
 * define data which will replace the values of the corresponding
 * property or field in any given entity.
 *
 * The difference between a field a a property is that a field
 * is managed by Drupal's Field system, whereas a property is not.
 * Example of fields include field_image, which define images in
 * articles; examples of properties include the user entity's
 * picture property, and the title of nodes.
 */
class RealisticDummyContentAPIFieldModifier extends RealisticDummyContentAPIEntityBase {
  /**
   * Get properties for the entity, for example user's picture or node's name.
   */
  function GetProperties() {
    $modifiable_properties = array();
    $fields = $this->GetFields();
    foreach ((array)$this->GetEntity() as $property => $info) {
      if (!in_array($property, $fields) && $this->filter($property)) {
        $modifiable_properties[] = $property;
      }
    }
    return $modifiable_properties;
  }

  /**
   * Get fields for the entity, for example body or field_image.
   */
  function GetFields() {
    $modifiable_fields = array();
    $entity = $this->GetEntity();
    $type = $this->GetType();
    $bundle = $this->GetBundle();
    $fields = field_info_fields();
    foreach ($fields as $field => $info) {
      if (isset($info['bundles'][$type]) && is_array($info['bundles'][$type]) && in_array($this->GetBundle(), $info['bundles'][$type]) && $this->filter($field)) {
        $modifiable_fields[] = $field;
      }
    }
    return $modifiable_fields;
  }

  /**
   * Override RealisticDummyContentAPIEntityBase::Modify().
   */
  function Modify() {
    foreach ($this->GetFields() as $field) {
      $files = $this->GetCandidateFiles($field);
      $this->ChangeField($files, $field);
    }
    $fields = $this->GetProperties();
    foreach ($fields as $field) {
      $files = $this->GetCandidateFiles($field);
      $this->ChangeProperty($files, $field);
    }
  }

  /**
   * Change a field, if possible, based on a value found in a list of files.
   *
   * Entities have fields (such as field_image) and properties (such as
   * picture). They are dealt with differently. This function changes a field.
   *
   * @param $files
   *   An array of file objects where we can fetch replacement content.
   * @param $field
   *   The name of the field to change.
   */
  function ChangeField($files, $field) {
    $value = $this->ValueFromFile($files, TRUE);
    if ($value) {
      $entity = $this->GetEntity();
      $entity->{$field}[LANGUAGE_NONE][0] = $value;
      $this->SetEntity($entity);
    }
  }

  /**
   * Change a property, if possible, based on a value found in a list of files.
   *
   * Entities have fields (such as field_image) and properties (such as
   * picture). They are dealt with differently. This function changes a
   * property.
   *
   * @param $files
   *   An array of file objects where we can fetch replacement content.
   * @param $property
   *   The name of the property to change.
   */
  function ChangeProperty($files, $property) {
    $value = $this->ValueFromFile($files);
    if ($value) {
      $entity = $this->GetEntity();
      $entity->{$property} = $value;
      $this->SetEntity($entity);
    }
  }

  /**
   * Generate a random number, or during tests, give the first available number.
   */
  function rand($start, $end) {
    return realistic_dummy_content_api_rand($start, $end);
  }

  /**
   * Given a list of files, return a value from one of them randomly.
   *
   * @TODO this function only supports image fields, picture properties, and
   * text areas for now, see https://drupal.org/node/2266639.
   *
   * @param $files
   *   An array of file objects
   *
   * @param $is_array
   *   In the case where the file object itself is being returned, should
   *   it be returned as an array as opposed to an object. (The user's "picture"
   *   property expects a different format than file fields).
   *
   * @return
   *   A file object or array, or an associative array with the keys "value" and
   *   "format", or NULL if there are no files to choose from or the files have
   *   the wrong extension.
   */
  function ValueFromFile($files, $is_array = FALSE) {
    if (count($files)) {
      $file = $files[$this->rand(0, count($files) - 1)];
      $filename = $file->filename;
      $extension = pathinfo($filename, PATHINFO_EXTENSION);
      $contents = file_get_contents($file->uri);
      switch ($extension) {
        case 'txt':
          return array(
            'value' => $contents,
            // @TODO this is not always the default, allow the user to change
            // this via a hook, or some other mechanism.
            'format' => 'filtered_html',
          );
          break;
        case 'png':
        case 'jpg':
        case 'gif':
          $random = md5($file->uri) . rand(1000000000, 9999999999);
          $file = file_save_data($contents, 'public://dummyfile' . $random . '.' . $extension);
          $file->uid = $this->GetUid();
          if ($is_array) {
            return (array)file_save($file);
          }
          else {
            return file_save($file);
          }
          break;
        default:
          break;
      }
    }
    return NULL;
  }

  /**
   * Get the uid property of this entity, or 0.
   *
   * @return
   *   The uid of the associated entity.
   */
  function GetUid() {
    $entity = $this->GetEntity();
    if (isset($entity->uid)) {
      return $entity->uid;
    }
    else {
      return 0;
    }
  }

  /**
   * Get all candidate files for a given field for this entity.
   */
  function GetCandidateFiles($field) {
    $candidate_files = array();
    foreach (module_list() as $module) {
      $filepath = DRUPAL_ROOT . '/' . drupal_get_path('module', $module) . '/realistic_dummy_content/fields/' . $this->GetType() . '/' . $this->GetBundle() . '/' . $field;
      $candidate_files = array_merge($candidate_files, array_values(file_scan_directory($filepath, '/.*\.(png|txt|gif|jpg)$/')));
    }
    $files = array();
    foreach ($candidate_files as $candidate_file) {
      if ($candidate_file->filename != 'README.txt') {
        $files[] = $candidate_file;
      }
    }
    return $files;
  }
}
