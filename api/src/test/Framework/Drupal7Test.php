<?php

namespace Drupal\realistic_dummy_content_api\Framwork;

/**
 * Required interface.
 */
interface FrameworkInterface {}

/**
 * Required dummy parent class.
 */
class Framework {}

namespace Drupal\realistic_dummy_content_api\Test;

require_once './api/src/Framework/Drupal7.php';

/**
 * Tests for \Drupal\realistic_dummy_content_api\Framework\Drupal7.
 *
 * @group realistic_dummy_content
 */
class Drupal7Test extends \PHPUnit_Framework_TestCase {

  /**
   * Tests Drupal7::implementEntityIsDummy().
   *
   * @dataProvider providerTestImplementEntityIsDummy
   */
  public function testImplementEntityIsDummy($account, $type, $expected) {
    $framework = $this->getMockBuilder('Drupal\realistic_dummy_content_api\Framework\Drupal7')
      ->setMethods(array('drupalSubstr'))
      ->getMock();;
    $framework->method('drupalSubstr')
      ->will($this->returnCallback('substr'));

    $result = $framework->implementEntityIsDummy($account, $type);
    $this->assertTrue($result === $expected, 'Account ' . serialize($account) . ' returned ' . serialize($result) . ' (expected result is ' . $expected . ')');
  }

  /**
   * Data provider for $this->testImplementEntityIsDummy().
   */
  public function providerTestImplementEntityIsDummy() {
    return array(
      array(
        (object) array(
          'mail' => 'whatever@example.com.invalid',
        ),
        'user',
        TRUE,
      ),
      array(
        (object) array(
          'mail' => 'whatever@example.com',
          'devel_generate' => TRUE,
        ),
        'user',
        TRUE,
      ),
      array(
        (object) array(
          'mail' => 'whatever@example.com',
        ),
        'user',
        FALSE,
      ),
      array(
        (object) array(
          'devel_generate' => TRUE,
        ),
        'node',
        TRUE,
      ),
      array(
        (object) array(),
        'node',
        FALSE,
      ),
    );
  }

}
