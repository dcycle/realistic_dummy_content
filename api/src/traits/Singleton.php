<?php

namespace Drupal\realistic_dummy_content_api\traits;

/**
 * A trait with represents a singleton.
 *
 * For any class for which only one instance is necessary, for example
 * Framework, you can add this trait (use Singleton), and then access
 * the unique instance using Framework::instance(). That way you will never
 * have more than once instance of a class.
 */
trait Singleton {

  /**
   * Interal instance variable used with the instance() method.
   *
   * @var object
   */
  static private $instance;

  /**
   * No one but us can call the constructor.
   */
  private function __construct() {
  }

  /**
   * Implements the Singleton design pattern.
   *
   * Only one instance of this class should exist per execution.
   *
   * @return object
   *   The single instance of a class which uses the
   *   Singleton trait.
   */
  static public function instance() {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

}
