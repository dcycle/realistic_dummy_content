<?php

namespace Drupal\realistic_dummy_content_api\includes;

use Drupal\realistic_dummy_content_api\traits\Singleton;

/**
 * Everything having to do with numbers and calculations.
 */
class Math {

  use Singleton;

  /**
   * Generate sequential number based on a hash.
   *
   * Returns the starting number on every call until the hash is changed, in
   * which case it returns the second number, and so on.
   *
   * The idea behind this is that for a single node, we might want to retrieve
   * the 3rd file for each field (they go together).
   *
   * In the above example, if the 3rd file does not exist, we will return the
   * first file, in order to never return a number which is outside the range of
   * start to end.
   *
   * @param int $start
   *   The first possible number in the range.
   * @param int $end
   *   The last possible number in the range.
   * @param string $hash
   *   The number returned by this function will be in sequence: each call to
   *   realistic_dummy_content_api_sequential()'s return is incremented by
   *   one, unless $hash is the same as in the last call, in which case the
   *   return will be the same as in the last call.
   * @param int $offset
   *   The offset, for example if the possible numbers are 3,4,5, and we are
   *   about to return 5 with an offset of 1, we will return 3.
   *
   * @return int
   *   A sequential number based on the $hash.
   *   Please see the description of the $hash parameter, above.
   */
  public function sequential($start, $end, $hash, $offset = 0) {
    static $static_hash = NULL;
    if (!$static_hash) {
      $static_hash = $hash;
    }
    static $current = NULL;
    if (!$current) {
      $current = $start;
    }
    if ($static_hash != $hash) {
      $static_hash = $hash;
      $current -= $start;
      $current++;
      $current %= ($end - $start + 1 + $offset);
      $current += $start;
    }

    if ($current > $end) {
      $return = $end;
    }
    elseif ($current < $start) {
      $return = $start;
    }
    else {
      $return = $current;
    }

    return $return;
  }

}
