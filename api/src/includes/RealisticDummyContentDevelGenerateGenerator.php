<?php

namespace Drupal\realistic_dummy_content_api\includes;

/**
 * The "devel generate" dummy content generator.
 */
class RealisticDummyContentDevelGenerateGenerator extends RealisticDummyContentGenerator {

  /**
   * {@inheritdoc}
   */
  public function implementGenerate() {
    module_load_include('inc', 'devel_generate');

    if ($this->getType() == 'node') {
      // See https://www.drupal.org/node/2324027
      $info = array(
        'node_types' => array(
          $this->getBundle() => $this->getBundle(),
        ),
        'users' => array(
          1,
        ),
        'title_length' => 3,
      );
      if ($this->getKill()) {
        devel_generate_content_kill($info);
      }
      for ($i = 0; $i < $this->getNum(); $i++) {
        devel_generate_content_add_node($info);
      }
    }
    elseif ($this->getType() == 'user') {
      devel_create_users($this->getNum(), $this->getKill());
    }
  }

}
