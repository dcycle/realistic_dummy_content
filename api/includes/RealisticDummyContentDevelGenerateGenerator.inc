<?php

/**
 * @file
 *
 * Define RealisticDummyContentDevelGenerateGenerator autoload class.
 */

class RealisticDummyContentDevelGenerateGenerator extends RealisticDummyContentGenerator {
  /**
   * @throws
   *   Exception
   */
  function _Generate_() {
    module_load_include('inc', 'devel_generate');

    if ($this->GetType() == 'node') {
      // see https://www.drupal.org/node/2324027
      $info = array(
        'node_types' => array(
          $this->GetBundle() => $this->GetBundle(),
        ),
        'users' => array(
          1,
        ),
        'title_length' => 3,
      );
      if ($this->GetKill()) {
        devel_generate_content_kill($info);
      }
      for ($i = 0; $i < $this->GetNum(); $i++) {
        devel_generate_content_add_node($info);
      }
    }
    elseif ($this->GetType() == 'user') {
      devel_create_users($this->GetNum(), $this->GetKill());
    }
  }
}
