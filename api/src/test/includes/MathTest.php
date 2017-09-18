<?php

namespace Drupal\realistic_dummy_content_api\Test;

require_once './api/src/traits/Singleton.php';
require_once './api/src/includes/Math.php';

use Drupal\realistic_dummy_content_api\includes\Math;

/**
 * Tests for \Drupal\realistic_dummy_content_api\includes\Math.
 *
 * @group realistic_dummy_content
 */
class MathTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests Math::sequential().
   *
   * @param int $start
   *   Start number passed to realistic_dummy_content_api_sequential().
   * @param int $end
   *   End number passed to realistic_dummy_content_api_sequential().
   * @param string $hash
   *   Hash passed to realistic_dummy_content_api_sequential().
   * @param int $offset
   *   The offset.
   * @param int $expected
   *   Expected result which realistic_dummy_content_api_sequential() is
   *   expected to return.
   *
   * @dataProvider providerTestSequential
   */
  public function testSequential($start, $end, $hash, $expected, $offset) {
    $math = Math::instance();

    $result = $math->sequential($start, $end, $hash, $offset);
    $this->assertTrue($result == $expected, 'Sequential number is as expected for ' . $start . ', ' . $end . ' with hash ' . $hash . ': [expected] ' . $expected . ' = [result] ' . $result);
  }

  /**
   * Data provider for $this->testSequential().
   */
  public function providerTestSequential() {
    return array(
      array(0, 3, 'a', 0, 0),
      array(0, 3, 'a', 0, 0),
      array(0, 3, 'a', 1, 1),
      array(0, 3, 'a', 2, 2),
      array(0, 3, 'a', 3, 3),
      array(0, 3, 'a', 4, 0),
      array(0, 3, 'a', 0, 0),
      array(0, 3, 'b', 0, 1),
      array(0, 3, 'b', 0, 1),
      array(0, 3, 'c', 0, 2),
      array(0, 3, 'c', 0, 2),
      array(0, 3, 'd', 0, 3),
      array(0, 2, 'd', 0, 2),
      array(10, 13, 'd', 0, 10),
      array(11, 12, 'd', 0, 11),
    );
  }

}
