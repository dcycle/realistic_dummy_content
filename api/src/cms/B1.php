<?php

namespace Drupal\realistic_dummy_content_api\cms;

// Explicitly load the Drupal 7 class from which we inherit.
module_load_include('php', 'realistic_dummy_content_api', 'src/cms/D7');

/**
 * Represents the Backdrop 1 CMS.
 */
class B1 extends D7 {

  /**
   * {@inheritdoc}
   */
  public function implementFileSave($drupal_file) {
    file_save($drupal_file);
    return $drupal_file;
  }

  /**
   * {@inheritdoc}
   */
  public function getDefaultNodeType() {
    return 'post';
  }

  /**
   * {@inheritdoc}
   */
  public function implementVocabularyIdentifier($vocabulary) {
    return $vocabulary->name;
  }

  /**
   * {@inheritdoc}
   */
  public function implementNewVocabularyTerm($vocabulary, $name) {
    $term = new \TaxonomyTerm(array(
      'name' => $name,
      'vocabulary' => $vocabulary->machine_name,
    ));
    $term->save();
    return $term;
  }

}
