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
   */
  static private $instance;

  /**
   * No one but us can call the constructor.
   */
  private function __construct() {}

  /**
   * Implements the Singleton design pattern.
   *
   * Only one instance of this class should exist per execution.
   *
   * @return class using IgniteSingletonTrait
   *   The single instance of the class.
   */
  static function instance() {
    if (!self::$instance) {
      self::$instance = new self();
    }
    return self::$instance;
  }

}
