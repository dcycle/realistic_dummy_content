<?php

namespace Drupal\Tests\realistic_dummy_content_api\Unit\includes;

use Drupal\realistic_dummy_content_api\includes\Math;
use PHPUnit\Framework\TestCase;

/**
 * Tests for \Drupal\realistic_dummy_content_api\includes\Math.
 *
 * @group realistic_dummy_content
 */
class MathTest extends TestCase {

  /**
   * Tests Math::sequential().
   *
   * @param int $start
   *   Start number passed to realistic_dummy_content_api_sequential().
   * @param int $end
   *   End number passed to realistic_dummy_content_api_sequential().
   * @param string $hash
   *   Hash passed to realistic_dummy_content_api_sequential().
   * @param int $expected
   *   Expected result which realistic_dummy_content_api_sequential() is
   *   expected to return.
   *
   * @dataProvider providerTestSequential
   */
  public function testSequential($start, $end, $hash, $expected) {
    $math = new Math();

    $result = $math->sequential($start, $end, $hash);
    $this->assertTrue($result == $expected, 'Sequential number is as expected for ' . $start . ', ' . $end . ' with hash ' . $hash . ': [expected] ' . $expected . ' = [result] ' . $result);
  }

  /**
   * Data provider for $this->testSequential().
   */
  public function providerTestSequential() {
    return [
      [0, 3, 'a', 0],
      [0, 3, 'a', 0],
      [0, 3, 'b', 1],
      [0, 3, 'b', 1],
      [0, 3, 'c', 2],
      [0, 3, 'c', 2],
      [0, 3, 'd', 3],
      [0, 2, 'd', 2],
      [10, 13, 'd', 10],
      [11, 12, 'd', 11],
    ];
  }

}
