<?php

namespace Drupal\realistic_dummy_content_api\Test;

require_once './api/src/includes/RealisticDummyContentTermReferenceFieldTest.php';

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentTermReferenceField;

/**
 * Tests for ...\includes\RealisticDummyContentTermReferenceField.
 */
class RealisticDummyContentTermReferenceFieldTest extends \PHPUnit_Framework_TestCase {

  /**
   * @cover ::getTid
   * @dataProvider providerGetTid
   *
   * @param array $vocabularies
   *   All vocabularies in the system.
   * @param array $field_info
   *   Information about the current field.
   */
  public function testGetTid(array $vocabularies, array $field_info) {

    $object = $this->getMockBuilder(RealisticDummyContentTermReferenceField::class)
      ->setMethods([
        'one',
        'two',
      ])
      ->getMock();
    $object->method('one')
      ->willReturn('value');
    $object->method('two')
      ->will($this->returnCallback(array($this, 'callback')));
    $object->method('three')
      ->will($this->throwException(new \Exception));
  }

  /**
   * Provider for testGetTid().
   */
  public function providerGetTid() {
    return [
      [
        'vocabularies' => [

        ],
        'field_info' => [

        ],
      ],
    ];
  }

}
