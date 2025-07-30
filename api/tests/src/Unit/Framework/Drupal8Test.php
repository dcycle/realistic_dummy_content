<?php

namespace Drupal\Tests\realistic_dummy_content_api\Unit\Framework;

use Drupal\realistic_dummy_content_api\Framework\Drupal8;
use PHPUnit\Framework\TestCase;

/**
 * Tests for \Drupal\realistic_dummy_content_api\Framework\Drupal8.
 *
 * @group realistic_dummy_content
 */
class Drupal8Test extends TestCase {

  /**
   * Test for setEntityProperty().
   *
   * @param string $message
   *   The test message.
   * @param mixed $entity
   *   The mock entity.
   * @param mixed $property
   *   The mock property.
   * @param mixed $value
   *   The mock value.
   * @param mixed $expected
   *   The expected resulting entity.
   *
   * @dataProvider providerSetEntityProperty
   */
  public function testSetEntityProperty(string $message, object &$entity, string $property, $value, $expected): void {
    $object = $this->createMock(Drupal8::class);

    $ref = new \ReflectionClass(Drupal8::class);
    if ($ref->hasMethod('setEntityProperty')) {
      $method = $ref->getMethod('setEntityProperty');
      $method->setAccessible(true);

      $output = $entity;
      $instance = new Drupal8(); // Or however you actually get an instance
      $instance->setEntityProperty($output, $property, $value);
    }

    if ($output != $expected) {
      print_r([
        'output' => $output,
        'expected' => $expected,
      ]);
    }
    $this->assertEquals($expected, $output, $message);
  }

  /**
   * Provider for testSetEntityProperty().
   */
  public static function providerSetEntityProperty(): array {
    $template = new class {
      public $whatever;

      public function set($param, $value) {
        $this->{$param} = $value;
      }
    };
  
    // Case 1: Direct string value
    $entity1 = clone $template;
    $expected1 = clone $template;
    $expected1->whatever = ['Hello World'];

    // Case 2: Array with 'set' key, still should assign 'Hello World' (string)
    $entity2 = clone $template;
    $expected2 = clone $template;
    $expected2->whatever = 'Hello World';

    return [
      [
        'message' => 'Base case',
        'entity' => $entity1,
        'property' => 'whatever',
        'value' => 'Hello World',
        'expected' => $expected1,
      ],
      [
        'message' => 'Value has "set" property',
        'entity' => $entity2,
        'property' => 'whatever',
        'value' => ['set' => 'Hello World'],
        'expected' => $expected2,
      ],
    ];
  }

}
