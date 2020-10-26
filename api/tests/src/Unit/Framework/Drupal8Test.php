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
   * @cover ::setEntityProperty
   * @dataProvider providerSetEntityProperty
   */
  public function testSetEntityProperty(string $message, $entity, $property, $value, $expected) {
    $object = $this->getMockBuilder(Drupal8::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $output = $entity;
    $object->setEntityProperty($output, $property, $value);

    if ($output != $expected) {
      print_r([
        'output' => $output,
        'expected' => $expected,
      ]);
    }

    $this->assertTrue($output == $expected, $message);
  }

  /**
   * Provider for testSetEntityProperty().
   */
  public function providerSetEntityProperty() {
    // @codingStandardsIgnoreStart
    $class1 = new class {
      function set($param, $value) {
        $this->{$param} = $value;
      }
    };
    // @codingStandardsIgnoreEnd

    $class2 = $class1;
    $class2->whatever = "Hello World";

    return [
      [
        'message' => 'Base case',
        'entity' => $class1,
        'property' => 'whatever',
        'value' => 'Hello World',
        'expected' => $class2,
      ],
      [
        'message' => 'Value has "set" property',
        'entity' => $class1,
        'property' => 'whatever',
        'value' => [
          'set' => 'Hello World',
        ],
        'expected' => $class2,
      ],
    ];
  }

}
