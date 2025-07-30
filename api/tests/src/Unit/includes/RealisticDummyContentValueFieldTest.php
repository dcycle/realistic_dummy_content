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
    $reflection = new \ReflectionClass(RealisticDummyContentValueField::class);
    $object = $reflection->newInstanceWithoutConstructor();
    $this->assertInstanceOf(RealisticDummyContentValueField::class, $object);
  }

}
