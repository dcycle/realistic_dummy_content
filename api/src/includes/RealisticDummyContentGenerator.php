<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * Abstract dummy content generator class.
 *
 * This module can generate dummy content using an extensible system of
 * "generators", which are subclasses of this class.
 */
abstract class RealisticDummyContentGenerator {

  /**
   * Whether or not to kill previous content.
   *
   * @var bool
   */
  private $kill;

  /**
   * The type of entity to generate.
   *
   * @var string
   */
  private $type;

  /**
   * The bundle of the entity to generate.
   *
   * @var mixed
   */
  private $bundle;

  /**
   * The amount of entities to generate.
   *
   * @var mixed
   */
  private $num;

  /**
   * Placeholder for more information about this entity generation.
   *
   * @var mixed
   */
  private $more;

  /**
   * Constructor.
   *
   * @param string $type
   *   An entity type such as "user" or "node".
   * @param mixed $bundle
   *   An entity bundle.
   * @param mixed $num
   *   Number of entities to generate.
   * @param array $more
   *   Can contain:
   *     kill => TRUE|FALSE.
   */
  public function __construct(string $type, $bundle, $num, array $more) {
    $this->type = $type;
    $this->bundle = $bundle;
    $this->num = $num;
    if (isset($more['kill']) && $more['kill']) {
      $this->kill = TRUE;
    }
    else {
      $this->kill = FALSE;
    }
  }

  /**
   * Getter for the bundle property.
   */
  public function getBundle() {
    return $this->bundle;
  }

  /**
   * Getter for the type property.
   */
  public function getType() {
    return $this->type;
  }

  /**
   * Getter for the kill property.
   */
  public function getKill() {
    return $this->kill;
  }

  /**
   * Getter for the num propert.
   */
  public function getNum() {
    return $this->num;
  }

  /**
   * Generate content.
   */
  public function generate() {
    $this->implementGenerate();
  }

  /**
   * Generate content. Used internally in the class.
   */
  abstract public function implementGenerate();

}
