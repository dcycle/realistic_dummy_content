<?php

/**
 * @file
 *
 * Define RealisticDummyContentFieldModifier autoload class.
 */

/**
 * Field modifier class.
 *
 * All manipulation of generated content to make it more realistic
 * passes through modifiers (direct or indirect subclasses of
 * RealisticDummyContentEntityBase).
 *
 * This class (RealisticDummyContentFieldModifier) allows active modules to put files
 * in a specific directory hierarchy resembling realistic_dummy_content/fields/
 * [entity_type]/[bundle]/[field_name], and for these files to define data which will
 * replace the values of the corresponding property or field in any given entity.
 *
 * The difference between a field and a property is that a field is managed by Drupal's
 * Field system, whereas a property is not. Example of fields include field_image, which
 * define images in articles (in a standard installation); examples of properties include
 * the user entity's picture property, and the title of nodes.
 *
 * Drupal stores field values differently depending on the type of field, and third-party
 * modules can define their own schemes for storing values; an extensible system has
 * been defined to allow any module (including this one) to define field formats
 * and interpret data from files. To do so, modules must implement
 * hook_realistic_dummy_content_field_manipular_alter(). Please see the example
 * in this module's .module file, with more documentation in
 * realistic_dummy_content_api.api.php. (Realistic Dummy Content API defines specific
 * manipulators for the fields image, text_with_summary, taxonomy_term_reference...).
 */
class RealisticDummyContentFieldModifier extends RealisticDummyContentEntityBase {
  /**
   * Get properties for the entity, for example user's picture or node's name.
   *
   * @return
   *   An array of RealisticDummyContentAttribute objects, keyed by attribute name,
   *   e.g. title => [RealisticDummyContentAttribute], field_image =>
   *   [RealisticDummyContentAttribute]
   */
  function GetProperties() {
    $modifiable_properties = array();
    $fields = $this->GetFields();
    foreach ((array)$this->GetEntity() as $property => $info) {
      if (!in_array($property, array_keys($fields)) && $this->filter($property)) {
        $this->AddModifier($modifiable_properties, 'property', $property);
      }
    }
    return $modifiable_properties;
  }

  /**
   * Get fields for the entity, for example body or field_image.
   *
   * @return
   *   An array of RealisticDummyContentAttribute objects, keyed by attribute name,
   *   e.g. title => [RealisticDummyContentAttribute], field_image =>
   *   [RealisticDummyContentAttribute]
   */
  function GetFields() {
    $modifiable_fields = array();
    $entity = $this->GetEntity();
    $type = $this->GetType();
    $bundle = $this->GetBundle();
    $fields = field_info_fields();
    foreach ($fields as $field => $info) {
      if (isset($info['bundles'][$type]) && is_array($info['bundles'][$type]) && in_array($this->GetBundle(), $info['bundles'][$type]) && $this->filter($field)) {
        $this->AddModifier($modifiable_fields, 'field', $field);
      }
    }
    return $modifiable_fields;
  }

  /**
   * Adds a modifier to a list of attribute modifiers.
   *
   * To abstract away the difference between fields and properties, we
   * call them all attributes. Modifiers will modify attributes depending on
   * what they are. For example, a user picture is modified differently than
   * an image in an article. This is managed through an extensible class
   * hierarchy. Modules, including this one, can implement
   * hook_realistic_dummy_content_attribute_manipular_alter() to determine
   * which class should modify which attribute (field or property).
   *
   * By default, we will consider that properties are text properties and that
   * fields' [value] property should be modified. This is not the case, however
   * for user pictures (which should load a file), body fields (which contain
   * a text format), and others. These are all defined in subclasses and can
   * be extended by module developers.
   *
   * @param &$modifiers
   *   Existing array of subclasses of RealisticDummyContentAttribute, to which
   *   new modifiers will be added.
   * @param $type
   *   Either 'property' or 'field'
   * @param $name
   *   Name of the property or field, for example 'body', 'picture', 'title',
   *  'field_image'.
   */
  function AddModifier(&$modifiers, $type, $name) {
    $class = '';
    switch ($type) {
      case 'property':
        $original_class = 'RealisticDummyContentTextProperty';
        $attribute_type = $name;
        break;
      case 'field':
        $original_class = 'RealisticDummyContentValueField';
        $field_info = field_info_field($name);
        $attribute_type = $field_info['type'];
        break;
      default:
        return;
        break;
    }
    $class = $original_class;
    drupal_alter('realistic_dummy_content_attribute_manipulator', $class, $type, $attribute_type);

    if (!$class) {
      // third-parties might want to signal that certain fields cannot be
      // modified (they can be too complex for the default modifier and do not yet
      // have a custom modifier).
      return;
    }
    // @TODO check if class is abstract
    elseif (class_exists($class)) {
      $modifier = new $class($this, $name);
    }
    else {
      watchdog('realistic_dummy_content_api', 'Class does not exist: @c. This is probably because a third-party module has implemented realistic_dummy_content_api_realistic_dummy_content_attribute_manipular_alter() with a class that cannot be implemented. @original will used instead.', array('@c' => $class, '@original' => $original_class));
      $modifier = new $original_class($this, $name);
    }

    if (isset($modifier)) {
      // It's OK to index by name because attributes and fields can never have
      // the same names.
      $modifiers[$name] = $modifier;
    }
  }

  /**
   * {@inheritdoc}
   */
  function Modify() {
    $attributes = $this->GetAttributes();
    foreach ($attributes as $attribute) {
      $attribute->Change();
    }
  }

  /**
   * Returns all fields and properties.
   *
   * We implement fields and properties as subclasses of the same parent class,
   * which defines a common interface for dealing with them.
   *
   * @return
   *   An array of RealisticDummyContentAttribute objects, keyed by attribute name,
   *   e.g. title => [RealisticDummyContentAttribute], field_image =>
   *   [RealisticDummyContentAttribute]
   */
  function GetAttributes() {
    return array_merge($this->GetFields(), $this->GetProperties());
  }

  /**
   * Generate a random number, or during tests, give the first available number.
   */
  function rand($start, $end) {
    $return = realistic_dummy_content_api_rand($start, $end, $this->GetHash());
    return $return;
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

}
