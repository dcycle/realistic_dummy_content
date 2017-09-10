<?php

namespace Drupal\realistic_dummy_content_api\Framework;

namespace Drupal\realistic_dummy_content_api\Test;

require_once './api/src/Framework/Mock.php';

/**
 * Tests for \Drupal\realistic_dummy_content_api\Framework\Mock.
 *
 * @group realistic_dummy_content
 */
class MockTest extends \PHPUnit_Framework_TestCase {

  /**
   * Smoke test.
   */
  public function testSmoke() {
    // @codingStandardsIgnoreStart
    // Ignoring coding standards for this test because we cannot use a
    // "use" statement after the first namespace, would have to be after
    // the second namespace.
    $this->assertTrue(class_exists(\Drupal\realistic_dummy_content_api\Framework\Mock::class));
    // @codingStandardsIgnoreEnd
  }

}
