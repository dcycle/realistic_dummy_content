<?php

namespace Drupal\Tests\realistic_dummy_content_api\Unit\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentTermReferenceField;
use PHPUnit\Framework\TestCase;

/**
 * Tests for RealisticDummyContentTermReferenceField class.
 *
 * @group realistic_dummy_content
 */
class RealisticDummyContentTermReferenceFieldTest extends TestCase {

  /**
   * Callback: dummy version of ::taxonomyLoadTree().
   */
  public function callbackTaxonomyLoadTree($vocabulary) {
    return $vocabulary['terms'];
  }

  /**
   * Callback: dummy version of ::termId().
   */
  public function callbackTermId($term) {
    return $term['id'];
  }

  /**
   * Callback: dummy version of ::vocabularyMachineName().
   */
  public function callbackVocabularyMachineName($vocabulary) {
    return $vocabulary['vid'];
  }

  /**
   * Test getTid()
   *
   * @param string $message
   *   A test message.
   * @param array $vocabularies
   *   All vocabularies in the system.
   * @param array $field_info
   *   Information about the current field.
   * @param bool $expect_exception
   *   Whether or not we are expecting an exception.
   * @param string $name
   *   The taxonomy name to pass to the function.
   * @param mixed $expected
   *   The expected result.
   *
   * @dataProvider providerGetTid
   */
  public function testGetTid(string $message, array $vocabularies, array $field_info, bool $expect_exception, string $name, $expected) {

    $object = $this->getMockBuilder(RealisticDummyContentTermReferenceField::class)
      ->onlyMethods([
        'getAllVocabularies',
        'fieldInfoField',
        'vocabularyMachineName',
        'taxonomyLoadTree',
        'termId',
        'termName',
        'newVocabularyTerm',
      ])
      ->disableOriginalConstructor()
      ->getMock();

    // Mocking method behaviors.
    $object->method('getAllVocabularies')->willReturn($vocabularies);
    $object->method('newVocabularyTerm')->willReturn(['id' => 'this-is-a-new-term']);
    $object->method('fieldInfoField')->willReturn(['settings' => ['allowed_values' => $field_info]]);

    $object->method('vocabularyMachineName')->willReturnCallback([$this, 'callbackVocabularyMachineName']);


    $object->method('taxonomyLoadTree')->willReturnCallback([$this, 'callbackTaxonomyLoadTree']);
    $object->method('termId')->willReturnCallback([$this, 'callbackTermId']);
    $object->method('termName')->willReturnCallback([$this, 'callbackTermId']);

    if ($expect_exception) {
      $this->expectException(\Exception::class);
    }

    // Call method under test.
    $result = $object->getTid($name);

    // Assert result.
    $this->assertEquals($expected, $result, $message);
  }

  /**
   * Data provider for testGetTid().
   *
   * @return array[]
   *   Test cases.
   */
  public static function providerGetTid(): array {
    return [
      [
        'message' => 'Exception if no vocabulary.',
        'vocabularies' => [],
        'field_info' => [],
        'expect_exception' => TRUE,
        'name' => '',
        'expected' => 0,
      ],
      [
        'message' => 'New term is created if none exists.',
        'vocabularies' => [
          ['vid' => 'first', 'terms' => []],
        ],
        'field_info' => [['vocabulary' => 'not-first']],
        'expect_exception' => FALSE,
        'name' => 'whatever',
        'expected' => 'this-is-a-new-term',
      ],
    ];
  }

}
