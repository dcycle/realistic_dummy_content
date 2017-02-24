<?php

namespace Drupal\realistic_dummy_content_api\cms;

interface FrameworkInterface {

  /**
   * Use devel generate to generate dummy content.
   *
   * @param array $info
   *   Can contain:
   *     entity_type: user|node
   *     node_types: array
   *     users: array of users who can own a node
   *     title_legth
   *     num: int
   *     kill: bool
   */
  public function develGenerate($info);

}
