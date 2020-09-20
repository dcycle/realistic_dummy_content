<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\traits\RealisticDummyContentDrupalTrait;
use Drupal\realistic_dummy_content_api\Framework\Framework;

/**
 * Represents a term reference field.
 */
class RealisticDummyContentTermReferenceField extends RealisticDummyContentField {

  use RealisticDummyContentDrupalTrait;

  /**
   * {@inheritdoc}
   */
  public function implementValueFromFile($file) : array {
    try {
      $termname = $file->value();
      if ($termname) {
        $return = Framework::instance()->formatProperty('tid',
          $this->getTid($termname));
        return $return;
      }
      return [];
    }
    catch (\Exception $e) {
      Framework::instance()->debug('Problem with taxonomy term: ' . $e->getMessage());
      return [];
    }
  }

  /**
   * Returns term id for a term which is either existing or created on the fly.
   *
   * Let's say an entity (node) contains a term reference to the taxonomy
   * vocabulary "location", and in the realistic dummy content file structure,
   * "Australia" is used for the location. If "Australia" exists as a
   * "location", then this function will return its tid. If not, the term will
   * be created, and then the tid will be returned.
   *
   * If two terms have the same name, the tid of the first will be returned.
   *
   * @param string $name
   *   The string for the taxonomy term.
   *
   * @return int
   *   The associated pre-existing or just-created tid of the first term
   *   with the desired name.
   *
   * @throws \Exception
   */
  public function getTid($name) {
    $vocabularies = $this->getAllVocabularies();
    $field_info = $this->fieldInfoField($this->getName());
    $candidate_existing_terms = [];
    foreach ($field_info['settings']['allowed_values'] as $setting) {
      $vocabulary_name = $setting['vocabulary'];
      foreach ($vocabularies as $vocabulary) {
        if ($this->vocabularyMachineName($vocabulary) == $vocabulary_name) {
          $candidate_existing_terms = array_merge($candidate_existing_terms, $this->taxonomyLoadTree($vocabulary));
          break 2;
        }
      }
    }
    foreach ($candidate_existing_terms as $candidate_existing_term) {
      $candidate_name = $this->termName($candidate_existing_term);
      if ($candidate_name == $name) {
        return $this->termId($candidate_existing_term);
      }
    }

    if (!isset($vocabulary)) {
      throw new \Exception('Expecting the taxonomy term reference to reference at least one vocabulary');
    }

    $term = $this->newVocabularyTerm($vocabulary, $name);

    return $this->termId($term);
  }

}
