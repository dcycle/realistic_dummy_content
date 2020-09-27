<?php

namespace Drupal\Tests\realistic_dummy_content_api\Unit\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentTermReferenceField;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ...\includes\RealisticDummyContentTermReferenceField.
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
      ->setMethods([
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
    $object->method('getAllVocabularies')
      ->willReturn($vocabularies);
    $object->method('newVocabularyTerm')
      ->willReturn(['id' => 'this-is-a-new-term']);
    $object->method('fieldInfoField')
      ->willReturn([
        'settings' => [
          'allowed_values' => $field_info,
        ],
      ]);
    $object->method('vocabularyMachineName')
      ->will($this->returnCallback([$this, 'callbackVocabularyMachineName']));
    $object->method('taxonomyLoadTree')
      ->will($this->returnCallback([$this, 'callbackTaxonomyLoadTree']));
    $object->method('termId')
      ->will($this->returnCallback([$this, 'callbackTermId']));
    $object->method('termName')
      // For our purposes termId and termName can be identical.
      ->will($this->returnCallback([$this, 'callbackTermId']));

    if ($expect_exception) {
      $this->expectException(\Exception::class);
    }
    $result = $object->getTid($name);
    $this->assertTrue($result == $expected, $message);
  }

  /**
   * Provider for testGetTid().
   */
  public function providerGetTid() {
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
        'message' => 'new term is created if none exists.',
        'vocabularies' => [
          [
            'vid' => 'first',
            'terms' => [],
          ],
        ],
        'field_info' => [
          [
            'vocabulary' => 'not-first',
          ],
        ],
        'expect_exception' => FALSE,
        'name' => 'whatever',
        'expected' => 'this-is-a-new-term',
      ],
      [
        'message' => 'new term is created if none exists in the vocabulary.',
        'vocabularies' => [
          [
            'vid' => 'first',
            'terms' => [
              [
                'id' => 'some-term',
              ],
            ],
          ],
        ],
        'field_info' => [
          [
            'vocabulary' => 'first',
          ],
        ],
        'expect_exception' => FALSE,
        'name' => 'whatever',
        'expected' => 'this-is-a-new-term',
      ],
      [
        'message' => 'new term is created if one exists in a different vocabulary.',
        'vocabularies' => [
          [
            'vid' => 'first',
            'terms' => [
              [
                'id' => 'some-term',
              ],
            ],
          ],
        ],
        'field_info' => [
          [
            'vocabulary' => 'not-first',
          ],
        ],
        'expect_exception' => FALSE,
        'name' => 'some-term',
        'expected' => 'this-is-a-new-term',
      ],
      [
        'message' => 'existing term is used if one exists in the target vocabulary.',
        'vocabularies' => [
          [
            'vid' => 'first',
            'terms' => [
              [
                'id' => 'some-term',
              ],
            ],
          ],
        ],
        'field_info' => [
          [
            'vocabulary' => 'first',
          ],
        ],
        'expect_exception' => FALSE,
        'name' => 'some-term',
        'expected' => 'some-term',
      ],
    ];
  }

}
