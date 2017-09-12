<?php

namespace Drupal\realistic_dummy_content_api\Test;

require_once './api/src/includes/RealisticDummyContentTermReferenceFieldTest.php';

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentTermReferenceField;

/**
 * Tests for ...\includes\RealisticDummyContentTermReferenceField.
 */
class RealisticDummyContentTermReferenceFieldTest extends \PHPUnit_Framework_TestCase {

  protected function callbackTaxonomyLoadTree($vocabulary) {
    return $vocabulary['terms'];
  }

  protected function callbackTermId($term) {
    return $term['id'];
  }

  protected function callbackVocabularyMachineName($vocabulary) {
    return md5(serialize($vocabulary));
  }

  /**
   * @cover ::getTid
   * @dataProvider providerGetTid
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
   * @param int $expected
   *   The expected result.
   */
  public function testGetTid(string $message, array $vocabularies, array $field_info, bool $expect_exception, string $name, int $expected) {

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
      ->will($this->returnCallback(array($this, 'callbackVocabularyMachineName')));
    $object->method('taxonomyLoadTree')
      ->will($this->returnCallback(array($this, 'callbackTaxonomyLoadTree')));
    $object->method('termId')
      ->will($this->returnCallback(array($this, 'callbackTermId')));
    $object->method('termName')
      // For our purposes termId and termName can be identical.
      ->will($this->returnCallback(array($this, 'callbackTermId')));

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
        'message' => '',
        'vocabularies' => [
        ],
        'field_info' => [
        ],
        'expect_exception' => FALSE,
        'name' => '',
        'expected' => 0,
      ],
    ];
  }

}
