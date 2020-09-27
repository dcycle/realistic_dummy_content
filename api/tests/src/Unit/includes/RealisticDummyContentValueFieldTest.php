<?php

namespace Drupal\Tests\realistic_dummy_content_api\Unit\includes;

use Drupal\realistic_dummy_content_api\includes\RealisticDummyContentValueField;
use PHPUnit\Framework\TestCase;

/**
 * Tests for ...\includes\RealisticDummyContentValueField.
 *
 * @group realistic_dummy_content
 */
class RealisticDummyContentValueFieldTest extends TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    $object = $this->getMockBuilder(RealisticDummyContentValueField::class)
      // NULL = no methods are mocked; otherwise list the methods here.
      ->setMethods(NULL)
      ->disableOriginalConstructor()
      ->getMock();

    $this->assertTrue(is_object($object));
  }

}
